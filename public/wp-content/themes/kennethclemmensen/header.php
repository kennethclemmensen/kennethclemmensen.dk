<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <title><?php wp_title(); ?></title>
    <?php wp_head(); ?>
</head>

<body data-image-text="<?php echo TranslationStrings::getImageText(); ?>"
      data-of-text="<?php echo TranslationStrings::getOfText(); ?>">
<header class="header">
    <a href="<?php bloginfo('url'); ?>" class="header__site-name">
        <?php bloginfo('name'); ?>
    </a>
    <a href="#" class="header__nav-trigger">
        <span class="header__nav-icon"></span>
    </a>
    <nav class="navigation">
        <?php wp_nav_menu(['theme_location' => 'main-menu']); ?>
    </nav>
</header>
<nav class="mobile-nav">
    <div class="mobile-nav__content">
        <?php
        wp_nav_menu([
            'theme_location' => 'mobile-menu',
            'walker' => new MobileMenuWalker()
        ]);
        ?>
    </div>
</nav>