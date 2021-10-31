<?php
$files = glob(__DIR__.'/includes/*.php');
foreach($files as $file) require_once $file;

/**
 * Use the wp_enqueue_scripts action to add and remove scripts and stylesheets
 */
add_action('wp_enqueue_scripts', function() : void {
	wp_enqueue_style('theme', get_template_directory_uri().'/css/style.min.css');
	wp_dequeue_style('wp-block-library');
	$libraries = 'libraries';
	wp_enqueue_script($libraries, get_template_directory_uri().'/js/dist/libraries.min.js', in_footer: true);
	wp_enqueue_script('compiled', get_template_directory_uri().'/js/dist/compiled.min.js', [$libraries], in_footer: true);
	wp_dequeue_script('jquery');
	wp_deregister_script('wp-embed');
});

/**
 * Use the init action to remove emoji scripts and setup the theme settings, translation strings and menus
 */
add_action('init', function() : void {
	ThemeSettings::getInstance();
	new TranslationStrings();
	register_nav_menus([
		ThemeHelper::getMainMenuKey() => 'Main menu'
	]);
	remove_action('admin_print_styles', 'print_emoji_styles');
	$priority = 7;
	remove_action('wp_head', 'print_emoji_detection_script', $priority);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
});

/**
 * Use the widgets_init action to register sidebars and a custom widget
 */
add_action('widgets_init', function() : void {
	register_sidebar([
		'name' => 'Footer',
		'id' => ThemeHelper::getFooterSidebarID(),
		'before_widget' => '<div class="footer__widget">',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => ''
	]);
	register_sidebar([
		'name' => 'Page not found',
		'id' => ThemeHelper::getPageNotFoundSidebarID(),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h1>',
		'after_title' => '</h1>'
	]);
	register_widget(IconWidget::class);
});

/**
 * Use the admin_menu action to remove the Posts and Comments menu pages
 */
add_action('admin_menu', function() : void {
	remove_menu_page('edit.php');
	remove_menu_page('edit-comments.php');
});

/**
 * Use the admin_bar_menu action to remove the New Post menu link
 */
$priority = 999;
add_action('admin_bar_menu', function(WP_Admin_Bar $wpAdminBar) : void {
	$wpAdminBar->remove_node('new-post');
}, $priority);

/**
 * Use the excerpt_length filter to set the length of the excerpt
 *
 * @return int the length of the excerpt
 */
add_filter('excerpt_length', function() : int {
	return 20;
});

/**
 * Use the excerpt_more filter to change the last part of the excerpt
 *
 * @return string the last part of the excerpt
 */
add_filter('excerpt_more', function() : string {
	return '...';
});

/**
 * Remove the meta generator tag
 */
remove_action('wp_head', 'wp_generator');

/**
 * Use the script_loader_tag filter to add the defer attribute and remove the type attribute
 *
 * @param string $tag the tag to add and remove the attributes from
 * @return string the tag
 */
add_filter('script_loader_tag', function(string $tag) : string {
	return (is_admin()) ? $tag : str_replace(" type='text/javascript'", ' defer', $tag);
});

/**
 * Use the customize_register action to remove the custom css tag
 *
 * @param WP_Customize_Manager $wpCustomizeManager the customize manager
 */
add_action('customize_register', function(WP_Customize_Manager $wpCustomizeManager) : void {
	$wpCustomizeManager->remove_section('custom_css');
});