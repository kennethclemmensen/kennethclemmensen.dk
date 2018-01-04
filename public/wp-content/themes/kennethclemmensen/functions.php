<?php
require_once 'includes/MobileMenuWalker.php';
require_once 'includes/ThemeHelper.php';
require_once 'includes/ThemeSettings.php';
require_once 'includes/TranslationStrings.php';

add_action('wp_enqueue_scripts', function() {
    $font_awesome = 'font-awesome';
    wp_enqueue_style($font_awesome, '//use.fontawesome.com/releases/v5.0.2/css/all.css');

    $style = 'theme-css';
    $style_file = '/css/style.css';
    $version = filemtime(get_template_directory().$style_file);
    wp_enqueue_style($style, get_template_directory_uri().$style_file, [$font_awesome], $version);

    $jquery = 'jquery';
    $cdnFile = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js';
    $localFile = get_template_directory_uri().'/js/libraries/jquery-3.2.1.min.js';
    ThemeHelper::addScriptWithLocalFallback($jquery, $cdnFile, $localFile);

    $vue_js = 'vue-js';
    $cdnFile = 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.13/vue.min.js';
    $localFile = get_template_directory_uri().'/js/libraries/vue-2.5.13.min.js';
    ThemeHelper::addScriptWithLocalFallback($vue_js, $cdnFile, $localFile, [], false, true);

    $vue_resource = 'vue-resource';
    $cdnFile = 'https://cdnjs.cloudflare.com/ajax/libs/vue-resource/1.3.4/vue-resource.min.js';
    $localFile = get_template_directory_uri().'/js/plugins/vue-resource-1.3.4.min.js';
    ThemeHelper::addScriptWithLocalFallback($vue_resource, $cdnFile, $localFile, [$vue_js], false, true);

    $script = 'theme-js';
    $script_file = '/js/minified/script.min.js';
    $version = filemtime(get_template_directory().$script_file);
    wp_enqueue_script($script, get_template_directory_uri().$script_file, [$jquery, $vue_js, $vue_resource], $version, true);
});

add_action('init', function() {
    new ThemeSettings();
    new TranslationStrings();
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

add_filter('tiny_mce_plugins', function(array $plugins) : array {
    return array_diff($plugins, ['wpemoji']);
});

add_filter('emoji_svg_url', '__return_false');

add_filter('excerpt_length', function() : int {
    return 20;
});

add_filter('excerpt_more', function() : string {
    return '...';
});

remove_action('wp_head', 'wp_generator');

function removeVersionQueryString(string $src) : string {
    $parts = explode('?ver', $src);
    return $parts[0];
}

add_filter('script_loader_src', function(string $src) : string {
    return removeVersionQueryString($src);
});

add_filter('style_loader_src', function(string $src) : string {
    return removeVersionQueryString($src);
});