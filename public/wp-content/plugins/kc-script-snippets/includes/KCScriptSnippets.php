<?php
namespace KCScriptSnippets\Includes;

/**
 * Class KCScriptSnippets contains methods to handle the functionality of the plugin
 * @package KCScriptSnippets\Includes
 */
class KCScriptSnippets {

    private $kcScriptSnippetsSettings;

    public const AFTER_START_BODY = 'kcss_after_start_body';

    /**
     * KCScriptSnippets constructor
     */
    public function __construct() {
        require_once 'KCScriptSnippetsSettings.php';
        $this->kcScriptSnippetsSettings = new KCScriptSnippetsSettings();
    }

    /**
     * Execute the plugin
     */
    public function execute() : void {
        $this->addScriptSnippets2Header();
        $this->addScriptSnippets2AfterStartBody();
        $this->addScriptSnippets2Footer();
    }

    /**
     * Use the kcss_after_start_body action to add script snippets after the start body tag
     */
    private function addScriptSnippets2Header() : void {
        add_action('wp_head', function() : void {
            echo $this->kcScriptSnippetsSettings->getHeaderScriptSnippets();
        });
    }

    /**
     * Use the wp_head action to add script snippets to the header
     */
    private function addScriptSnippets2AfterStartBody() : void {
        add_action(self::AFTER_START_BODY, function() : void {
            echo $this->kcScriptSnippetsSettings->getAfterStartBodyScriptSnippets();
        });
    }

    /**
     * Use the wp_footer action to add script snippets to the footer
     */
    private function addScriptSnippets2Footer() : void {
        add_action('wp_footer', function() : void {
            echo $this->kcScriptSnippetsSettings->getFooterScriptSnippets();
        });
    }
}