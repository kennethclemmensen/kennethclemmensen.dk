<?php
get_header();
?>
    <div class="page">
        <?php get_template_part('template-parts/slider'); ?>
        <section class="page__content">
            <?php dynamic_sidebar(ThemeHelper::getPageNotFoundSidebarID()); ?>
        </section>
    </div>
<?php
get_footer();