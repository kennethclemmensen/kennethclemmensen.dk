<?php
namespace KC\Core\Settings;

/**
 * The ISettings interface defines methods to handle settings
 */
interface ISettings {

	/**
	 * Create a settings page
	 */
	public function createSettingsPage() : void;
}