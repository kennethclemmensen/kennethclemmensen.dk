<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<title><?php wp_title(); ?></title>
	<?php wp_head(); ?>
</head>

<?php
$translationStrings = new TranslationStrings();
$themeService = new ThemeService();
$themeSettings = ThemeSettings::getInstance();
$mobileMenuAnimation = $themeSettings->getMobileMenuAnimation();
$mobileMenuClass = '';
switch($mobileMenuAnimation) {
	case MobileMenuAnimation::SlideLeft->value:
		$mobileMenuClass = 'mobile-menu--slide-left';
		break;
	case MobileMenuAnimation::SlideRight->value:
		$mobileMenuClass = 'mobile-menu--slide-right';
		break;
	default:
		break;
}
?>
<body <?php body_class(); ?> data-image-text="<?php echo $translationStrings->getTranslatedString(TranslationStrings::IMAGE); ?>"
	data-of-text="<?php echo $translationStrings->getTranslatedString(TranslationStrings::OF); ?>">
<?php wp_body_open(); ?>
<header class="header">
	<a href="<?php bloginfo('url'); ?>" class="header__site-name">
		<?php bloginfo('name'); ?>
	</a>
	<a href="#" class="header__mobile-menu-trigger" id="mobile-menu-trigger">
		<span class="header__icon"></span>
	</a>
	<nav class="menu">
		<?php wp_nav_menu(['theme_location' => $themeService->getMainMenuKey()]); ?>
	</nav>
</header>
<nav class="mobile-menu <?php echo $mobileMenuClass; ?>" id="mobile-menu">
	<div class="mobile-menu__content">
		<?php
		wp_nav_menu([
			'theme_location' => $themeService->getMainMenuKey(),
			'walker' => new MobileMenuWalker()
		]);
		?>
	</div>
</nav>