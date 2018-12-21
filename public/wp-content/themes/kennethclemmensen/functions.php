<?php
require_once 'includes/IconWidget.php';
require_once 'includes/MobileMenuWalker.php';
require_once 'includes/ThemeHelper.php';
require_once 'includes/ThemeSettings.php';
require_once 'includes/TranslationStrings.php';

/**
 * Use the wp_enqueue_scripts action to add scripts and stylesheets
 */
add_action('wp_enqueue_scripts', function() : void {
    $fontAwesome = 'font-awesome';
    $cdnFile = 'https://use.fontawesome.com/releases/v5.6.3/css/all.css';
    $localFile = '/css/fontawesome-5.6.3.min.css';
    $version = filemtime(get_template_directory().$localFile);
    ThemeHelper::addStyleWithLocalFallback($fontAwesome, $cdnFile, get_template_directory_uri().$localFile, $version);

    $lightbox = 'lightbox';
    $cdnFile = 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.0/css/lightbox.min.css';
    $localFile = '/css/lightbox-2.10.0.min.css';
    $version = filemtime(get_template_directory().$localFile);
    ThemeHelper::addStyleWithLocalFallback($lightbox, $cdnFile, get_template_directory_uri().$localFile, $version);

    $styleFile = '/css/style.css';
    $version = filemtime(get_template_directory().$styleFile);
    wp_enqueue_style('theme-css', get_template_directory_uri().$styleFile, [$fontAwesome, $lightbox], $version);

    $jquery = 'jquery';
    $cdnFile = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js';
    $localFile = '/js/libraries/jquery-3.3.1.min.js';
    $version = filemtime(get_template_directory().$localFile);
    ThemeHelper::addScriptWithLocalFallback($jquery, $cdnFile, get_template_directory_uri().$localFile, $version);

    $vue = 'vue';
    $cdnFile = 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.21/vue.min.js';
    $localFile = '/js/libraries/vue-2.5.21.min.js';
    $version = filemtime(get_template_directory().$localFile);
    ThemeHelper::addScriptWithLocalFallback($vue, $cdnFile, get_template_directory_uri().$localFile, $version);

    $lightbox = 'lightbox-js';
    $cdnFile = 'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.0/js/lightbox.min.js';
    $localFile = '/js/plugins/lightbox-2.10.0.min.js';
    $version = filemtime(get_template_directory().$localFile);
    ThemeHelper::addScriptWithLocalFallback($lightbox, $cdnFile, get_template_directory_uri().$localFile, $version);

    $scriptFile = '/js/minified/script.min.js';
    $version = filemtime(get_template_directory().$scriptFile);
    wp_enqueue_script('theme-js', get_template_directory_uri().$scriptFile, $version, [$jquery, $vue, $lightbox], true);
});

/**
 * Use the init action to remove emoji scripts and setup the theme settings, translation strings and menus
 */
add_action('init', function() : void {
    ThemeSettings::getInstance();
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
 * Use the script_loader_src filter to remove the version query string from scripts
 *
 * @param string $src the source to remove the version query string from
 * @return string the source without the version query string
 */
add_filter('script_loader_src', function(string $src) : string {
    return ThemeHelper::removeVersionQueryString($src);
});

/**
 * Use the style_loader_src filter to remove the version query string from stylesheets
 *
 * @param string $src the source to remove the version query string from
 * @return string the source without the version query string
 */
add_filter('style_loader_src', function(string $src) : string {
    return ThemeHelper::removeVersionQueryString($src);
});

/**
 * Use the script_loader_tag action to add the defer attribute and remove the type attribute
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

/**
 * Filters the canonical redirect url
 */
add_filter('redirect_canonical', '__return_false');

/**
 * Disallow file edit for themes and plugins
 */
define('DISALLOW_FILE_EDIT', true);