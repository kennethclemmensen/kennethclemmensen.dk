<?php
namespace KC\Core;

/**
 * The ISettings interface defines methods to handle settings
 */
interface ISettings {

    /**
     * Create a settings page
     */
    public function createSettingsPage() : void;
}