<?php
/**
 * The Action enum defines the actions
 */
enum Action: string {
	case AdminBarMenu = 'admin_bar_menu';
	case AdminInit = 'admin_init';
	case AdminMenu = 'admin_menu';
	case AdminPrintScripts = 'admin_print_scripts';
	case AdminPrintStyles = 'admin_print_styles';
	case CustomizeRegister = 'customize_register';
	case Init = 'init';
	case WidgetsInit = 'widgets_init';
	case WpBodyOpen = 'wp_body_open';
	case WpEnqueueScripts = 'wp_enqueue_scripts';
	case WpFooter = 'wp_footer';
	case WpHead = 'wp_head';
	case WpPrintStyles = 'wp_print_styles';
}