<?php
add_action('wp_enqueue_scripts', function() {
	$font_awesome = 'font-awesome';
	wp_register_style($font_awesome, '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_enqueue_style($font_awesome);

	$style = 'theme-css';
	$style_file = '/css/style.css';
	$version = filemtime(get_template_directory().$style_file);
	wp_enqueue_style($style, get_template_directory_uri().$style_file, [], $version);

	$jquery = 'jquery';
	wp_deregister_script($jquery);
	wp_enqueue_script($jquery, '//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js', [], false, true);

	$script = 'theme-js';
	$script_file = '/js/minified/script.min.js';
	$version = filemtime(get_template_directory().$script_file);
	wp_enqueue_script($script, get_template_directory_uri().$script_file, [$jquery], $version, true);
});

/*add_filter('style_loader_tag', function($tag) {
	return str_replace(' href', 'async href', $tag);
});

add_filter('script_loader_tag', function($tag) {
	return str_replace(' src', 'async src', $tag);
});*/

add_action('init', function() {
	register_nav_menus([
		'mobile-menu' => 'Mobile menu',
		'main-menu' => 'Main menu'
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

add_filter('tiny_mce_plugins', function($plugins) {
	return (is_array($plugins)) ? array_diff($plugins, ['wpemoji']) : [];
});

add_filter('emoji_svg_url', '__return_false');

add_filter('excerpt_length', function() {
	return 20;
});

add_filter('excerpt_more', function() {
	return '...';
});

remove_action('wp_head', 'wp_generator');

add_filter('get_search_form', function(string $form) {
	return str_replace('value="Search"', 'value="Søg"', $form);
});