<?php
namespace KC\Data\Database;

/**
 * The DatabaseManager class contains functionality to manage the database.
 * The class cannot be inherited.
 */
final class DatabaseManager {

	/**
	 * Update the post_parent column for a post
	 * 
	 * @param int $postId the post id
	 * @param int $parentId the parent id
	 */
	public function updatePostParent(int $postId, int $parentId) : void {
		global $wpdb;
		$wpdb->update($wpdb->prefix.'posts', ['post_parent' => $parentId], ['ID' => $postId]);
	}

	/**
	 * Get the database structure
	 * 
	 * @return string the database structure
	 */
	public function getDatabaseStructure() : string {
		global $wpdb;
		$structure = '';
		$tables = $wpdb->get_results('SHOW TABLES LIKE "'.$wpdb->prefix.'%";', ARRAY_N);
		foreach($tables as $table) {
			$structure .= $this->getTableStructure($table[0]);
		}
		return $structure;
	}

	/**
	 * Get the table structure based on the table name
	 * 
	 * @param string $tableName the table name
	 * @return string the table structure
	 */
	private function getTableStructure(string $tableName) : string {
		global $wpdb;
		$createTable = $wpdb->get_row('SHOW CREATE TABLE '.$tableName.';', ARRAY_N);
		$structure = 'DROP TABLE IF EXISTS `'.$tableName.'`;'.PHP_EOL;
		$structure .= $createTable[1].';'.PHP_EOL;
		$structure .= 'LOCK TABLES `'.$tableName.'` WRITE;'.PHP_EOL;
		$structure .= $this->getTableContent($tableName);
		$structure .= 'UNLOCK TABLES;'.PHP_EOL.PHP_EOL;
		return $structure;
	}

	/**
	 * Get the table content based on the table name
	 * 
	 * @param string $tableName the table name
	 * @return string the table content
	 */
	private function getTableContent(string $tableName) : string {
		global $wpdb;
		$content = '';
		$tableRows = $wpdb->get_results('SELECT * FROM '.$tableName.';', ARRAY_N);
		$count = count($tableRows);
		if($count > 0) {
			$content .= 'INSERT INTO `'.$tableName.'` VALUES ';
			foreach($tableRows as $key => $tableRow) {
				$values = '(';
				$c = count($tableRow);
				foreach($tableRow as $k => $data) {
					$values .= ($data === null) ? 'NULL' : "'".addslashes($data)."'";
					if($k !== $c - 1) $values .= ',';
				}
				$content .= $values.')';
				if($key !== $count - 1) $content .= ',';
			}
			$content .= ';'.PHP_EOL;
		}
		return $content;
	}
}