<?php
namespace KC\Core\Settings;

use KC\Core\Security\SecurityService;

/**
 * The BaseSettings class contains basic functionality to handle settings
 */
abstract class BaseSettings {

	protected readonly string $settingsPage;
	protected readonly string $settingsName;
	protected readonly array | bool $settings;

	/**
	 * BaseSettings constructor
	 * 
	 * @param string $settingsPage the settings page
	 * @param string $settingsName the settings name
	 */
	protected function __construct(string $settingsPage, string $settingsName) {
		$this->settingsPage = $settingsPage;
		$this->settingsName = $settingsName;
		$this->settings = get_option($this->settingsName);
	}

	/**
	 * Create a settings page
	 */
	public abstract function createSettingsPage() : void;

	/**
	 * Register a setting with a name
	 * 
	 * @param string $name the name of the setting
	 */
	protected function registerSetting(string $name) : void {
		register_setting($name, $name, [
			'sanitize_callback' => function(array $input) : array {
				$securityService = new SecurityService();
				return $securityService->validateSettingInputs($input);
			}
		]);
	}

	/**
	 * Convert a binary string to a hexadecimal string
	 * 
	 * @param string $binaryString the binary string to convert
	 * @return string the hexadecimal string
	 */
	protected function convertToHexadecimal(string $binaryString) : string {
		return bin2hex($binaryString);
	}

	/**
	 * Convert a hexadecimal string to a binary string
	 * 
	 * @param string $hexadecimalString the hexadecimal string to convert
	 * @return string the binary string
	 */
	protected function convertToBinary(string $hexadecimalString) : string {
		return hex2bin($hexadecimalString);
	}

	/**
	 * Show tabs
	 * 
	 * @param array $tabs the tabs to show
	 */
	protected function showTabs(array $tabs) : void {
		settings_errors();
		$currentTab = (isset($_GET['tab'])) ? $_GET['tab'] : array_key_first($tabs);
		$activeTabClass = 'nav-tab-active';
		$content = '';
		?>
		<div class="wrap">
			<h2 class="nav-tab-wrapper">
				<?php
				foreach($tabs as $key => $tab) {
					if($currentTab === $key) {
						$class = $activeTabClass;
						$content = $tab['content'];
					} else {
						$class = '';
					}
					echo '<a href="?page='.$this->settingsPage.'&tab='.$key.'" class="nav-tab '.$class.'">'.$tab['title'].'</a>';
				}
				?>
			</h2>
			<?php
			if ($content !== '') {
				echo $content();
			}
			?>
		</div>
		<?php
	}

	/**
	 * Add a management page
	 * 
	 * @param string $title the title of the management page
	 * @param string $capability the capability required to access the management page
	 * @param string $menuSlug the menu slug for the management page
	 * @param callable $callback the callback function to display the management page
	 */
	protected function addManagementPage(string $title, string $capability, string $menuSlug, callable $callback) : void {
		add_management_page($title, $title, $capability, $menuSlug, $callback);
	}

	/**
	 * Add a submenu page
	 * 
	 * @param string $parentSlug the parent slug for the submenu page
	 * @param string $title the title of the submenu page
	 * @param string $capability the capability required to access the submenu page
	 * @param string $menuSlug the menu slug for the submenu page
	 * @param callable $callback the callback function to display the submenu page
	 */
	protected function addSubmenuPage(string $parentSlug, string $title, string $capability, string $menuSlug, callable $callback) : void {
		add_submenu_page('edit.php?post_type='.$parentSlug, $title, $title, $capability, $menuSlug, $callback);
	}
}