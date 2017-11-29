<?php
require_once 'includes/MobileMenuWalker.php';
require_once 'includes/polylang-translation-strings.php';

add_action('wp_enqueue_scripts', function() {
	$font_awesome = 'font-awesome';
	wp_register_style($font_awesome, '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_enqueue_style($font_awesome);

	$style = 'theme-css';
	$style_file = '/css/style.css';
	$version = filemtime(get_template_directory().$style_file);
	wp_enqueue_style($style, get_template_directory_uri().$style_file, [$font_awesome], $version);

	$jquery = 'jquery';
	wp_deregister_script($jquery);
	wp_enqueue_script($jquery, '//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js', [], false, true);

	$vue_js = 'vue-js';
	wp_enqueue_script($vue_js, '//cdnjs.cloudflare.com/ajax/libs/vue/2.5.8/vue.min.js', [], false, true);

	$vue_resource = 'vue-resource';
	wp_enqueue_script($vue_resource, '//cdnjs.cloudflare.com/ajax/libs/vue-resource/1.3.4/vue-resource.min.js', [$vue_js], false, true);

	$script = 'theme-js';
	$script_file = '/js/minified/script.min.js';
	$version = filemtime(get_template_directory().$script_file);
	wp_enqueue_script($script, get_template_directory_uri().$script_file, [$jquery, $vue_js, $vue_resource], $version, true);
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

function remove_version_query_string($src) {
    $parts = explode('?ver', $src);
    return $parts[0];
}

add_filter('script_loader_src', function($src) {
    return remove_version_query_string($src);
});

add_filter('style_loader_src', function($src) {
    return remove_version_query_string($src);
});

function get_breadcrumb() {
    global $post;
    if(!is_front_page()) {
        $pages[] = $post->ID;
        $parent = $post->post_parent;
        while($parent !== 0) {
            $page = get_post($parent);
            $pages[] = $page->ID;
            $parent = $page->post_parent;
        }
    }
    $pages[] = get_option('page_on_front');
    return array_reverse($pages);
}