<?php
require_once 'includes/IconWidget.php';
require_once 'includes/MobileMenuWalker.php';
require_once 'includes/ThemeHelper.php';
require_once 'includes/ThemeSettings.php';
require_once 'includes/TranslationStrings.php';

add_action('wp_enqueue_scripts', function() : void {
    $fontAwesome = 'font-awesome';
    $cdnFile = 'https://use.fontawesome.com/releases/v5.1.1/css/all.css';
    $localFile = '/css/fontawesome-5.1.1.min.css';
    $version = filemtime(get_template_directory().$localFile);
    ThemeHelper::addStyleWithLocalFallback($fontAwesome, $cdnFile, get_template_directory_uri().$localFile, [], $version);

    $styleFile = '/css/style.css';
    $version = filemtime(get_template_directory().$styleFile);
    wp_enqueue_style('theme-css', get_template_directory_uri().$styleFile, [$fontAwesome], $version);

    $jquery = 'jquery';
    $cdnFile = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js';
    $localFile = '/js/libraries/jquery-3.3.1.min.js';
    $version = filemtime(get_template_directory().$localFile);
    ThemeHelper::addScriptWithLocalFallback($jquery, $cdnFile, get_template_directory_uri().$localFile, [], $version);

    $vueJS = 'vue-js';
    $cdnFile = 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.min.js';
    $localFile = '/js/libraries/vue-2.5.16.min.js';
    $version = filemtime(get_template_directory().$localFile);
    ThemeHelper::addScriptWithLocalFallback($vueJS, $cdnFile, get_template_directory_uri().$localFile, [], $version);

    $vueResource = 'vue-resource';
    $cdnFile = 'https://cdnjs.cloudflare.com/ajax/libs/vue-resource/1.5.1/vue-resource.min.js';
    $localFile = '/js/plugins/vue-resource-1.5.1.min.js';
    $version = filemtime(get_template_directory().$localFile);
    ThemeHelper::addScriptWithLocalFallback($vueResource, $cdnFile, get_template_directory_uri().$localFile, [$vueJS], $version);

    $scriptFile = '/js/minified/script.min.js';
    $version = filemtime(get_template_directory().$scriptFile);
    wp_enqueue_script('theme-js', get_template_directory_uri().$scriptFile, [$jquery, $vueJS, $vueResource], $version, true);
});

add_action('init', function() : void {
    new ThemeSettings();
    new TranslationStrings();
    register_nav_menus([
        ThemeHelper::getMobileMenuKey() => 'Mobile menu',
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

add_action('admin_menu', function() : void {
    remove_menu_page('edit.php');
    remove_menu_page('edit-comments.php');
});

add_filter('excerpt_length', function() : int {
    return 20;
});

add_filter('excerpt_more', function() : string {
    return '...';
});

remove_action('wp_head', 'wp_generator');

add_filter('script_loader_src', function(string $src) : string {
    return ThemeHelper::removeVersionQueryString($src);
});

add_filter('style_loader_src', function(string $src) : string {
    return ThemeHelper::removeVersionQueryString($src);
});

add_filter('script_loader_tag', function(string $tag) : string {
    return str_replace(" type='text/javascript'", '', $tag);
});

define('DISALLOW_FILE_EDIT', true);