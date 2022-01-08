<?php
namespace KC\Core\Modules;

/**
 * The IModule interface defines a module
 */
interface IModule {

	/**
	 * Setup the module
	 */
	public function setupModule() : void;
}