<?php
get_header();
?>
    <div class="page">
        <?php ThemeHelper::loadSliderTemplate(); ?>
        <section class="page__content">
            <?php dynamic_sidebar(ThemeHelper::getPageNotFoundSidebarID()); ?>
        </section>
    </div>
<?php
get_footer();