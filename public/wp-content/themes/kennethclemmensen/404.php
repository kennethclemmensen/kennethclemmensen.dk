<?php
get_header();
?>
    <div class="page">
        <?php get_template_part('partials/slider'); ?>
        <section class="page__content">
            <?php dynamic_sidebar('page-not-found'); ?>
        </section>
    </div>
<?php
get_footer();