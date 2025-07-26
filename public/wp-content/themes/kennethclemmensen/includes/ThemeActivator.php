<?php
/**
 * Provides functionality to activate and run the theme
 */
final readonly class ThemeActivator {

	private readonly ThemeService $themeService;

	/**
	 * ThemeActivator constructor
	 */
	public function __construct() {
		$this->themeService = new ThemeService();
	}

	/**
	 * Activate the theme
	 */
	public function activate() : void {
		add_action(Action::Init->value, function() : void {
			ThemeSettings::getInstance();
			new TranslationStrings();
			register_nav_menus([
				$this->themeService->getMainMenuKey() => 'Main menu'
			]);
			$this->removeEmojiActions();
		});
		add_action(Action::AdminInit->value, function() : void {
			$this->removeEmojiActions();
		});
	}

	/**
	 * Run the theme
	 */
	public function run() : void {
		$this->addStylesAndScripts();
		$this->setupWidgets();
		$this->setupAdminMenu();
		define('DISALLOW_FILE_EDIT', true);
		add_filter(Filter::ExcerptLength->value, function() : int {
			return 20;
		});
		add_filter(Filter::ExcerptMore->value, function() : string {
			return '...';
		});
		remove_action(Action::WpHead->value, 'wp_generator');
	}

	/**
	 * Remove the emoji actions and filters
	 */
	private function removeEmojiActions() : void {
		$emojiStyles = 'print_emoji_styles';
		$emojiScript = 'print_emoji_detection_script';
		$staticizeEmoji = 'wp_staticize_emoji';
		$priority = 7;
		remove_action(Action::AdminPrintStyles->value, $emojiStyles);
		remove_action(Action::WpHead->value, $emojiScript, $priority);
		remove_action(Action::AdminPrintScripts->value, $emojiScript);
		remove_action(Action::WpPrintStyles->value, $emojiStyles);
		remove_filter(Filter::WpMail->value, 'wp_staticize_emoji_for_email');
		remove_filter(Filter::TheContentFeed->value, $staticizeEmoji);
		remove_filter(Filter::CommentTextRss->value, $staticizeEmoji);
	}

	/**
	 * Add styles and scripts
	 */
	private function addStylesAndScripts() : void {
		add_action(Action::WpEnqueueScripts->value, function() : void {
			$jsLibraries = 'js-libraries';
			$compiled = 'compiled';
			$cssLibraries = 'css-libraries';
			wp_enqueue_style($cssLibraries, get_template_directory_uri().'/dist/libraries.css');
			wp_enqueue_style('theme', get_template_directory_uri().'/dist/default.css', [$cssLibraries]);
			wp_dequeue_style('wp-block-library');
			wp_enqueue_script($jsLibraries, get_template_directory_uri().'/dist/libraries.js', args: ['in_footer' => true]);
			wp_enqueue_script($compiled, get_template_directory_uri().'/dist/default.js', [$jsLibraries], args: ['in_footer' => true]);
			wp_localize_script($compiled, 'httpHeaderValue', [
				'nonce' => wp_create_nonce('wp_rest')
			]);
			wp_dequeue_script('jquery');
			wp_deregister_script('wp-embed');
		});
		add_filter(Filter::ScriptLoaderTag->value, function(string $tag) : string {
			return (is_admin()) ? $tag : str_replace(" type='text/javascript'", ' defer', $tag);
		});
	}

	/**
	 * Setup the widgets
	 */
	private function setupWidgets() : void {
		add_action(Action::WidgetsInit->value, function() : void {
			register_sidebar([
				'name' => 'Footer',
				'id' => $this->themeService->getFooterSidebarID(),
				'before_widget' => '<div class="footer__widget">',
				'after_widget' => '</div>',
				'before_title' => '',
				'after_title' => ''
			]);
			register_sidebar([
				'name' => 'Page not found',
				'id' => $this->themeService->getPageNotFoundSidebarID(),
				'before_widget' => '',
				'after_widget' => '',
				'before_title' => '<h1>',
				'after_title' => '</h1>'
			]);
			register_widget(IconWidget::class);
		});
	}

	/**
	 * Setup the admin menu
	 */
	private function setupAdminMenu() : void {
		add_action(Action::AdminMenu->value, function() : void {
			remove_menu_page('edit.php');
			remove_menu_page('edit-comments.php');
		});
		$priority = 999;
		add_action(Action::AdminBarMenu->value, function(WP_Admin_Bar $wpAdminBar) : void {
			$wpAdminBar->remove_node('new-post');
		}, $priority);
		add_action(Action::CustomizeRegister->value, function(WP_Customize_Manager $wpCustomizeManager) : void {
			$wpCustomizeManager->remove_section('custom_css');
		});
	}
}