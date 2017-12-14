<?php
get_header();
?>
    <div class="page">
        <?php get_template_part('partials/slider'); ?>
        <section class="page__content">
            <h1>Siden blev ikke fundet</h1>
            <p>
                Siden du søgte efter blev ikke fundet. For at finde siden kan du kigge under
                <a href="/sitemap">Sitemap.</a>
            </p>
            <?php the_content(); ?>
        </section>
    </div>
<?php
get_footer();