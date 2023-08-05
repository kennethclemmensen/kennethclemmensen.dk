<?php
require_once __DIR__.'/settings/BaseSettings.php';
$directories = ['includes', 'settings', 'widgets'];
foreach($directories as $directory) {
	$directoryIterator = new RecursiveDirectoryIterator(__DIR__.'/'.$directory);
	$recursiveIterator = new RecursiveIteratorIterator($directoryIterator);
	$files = new RegexIterator($recursiveIterator, '/^.+\.php$/');
	foreach($files as $file) {
		require_once $file->getPathname();
	}
}
$themeActivator = new ThemeActivator();
$themeActivator->activate();
$themeActivator->run();