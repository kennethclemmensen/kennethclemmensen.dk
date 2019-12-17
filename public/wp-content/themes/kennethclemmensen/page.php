<?php
get_header();
while(have_posts()) {
    the_post();
    ?>
    <div class="page">
        <?php
        get_template_part('template-parts/slider');
        get_template_part('template-parts/breadcrumb');
        ?>
        <section class="page__content">
            <h1><?php the_title(); ?></h1>
            <?php the_content(); ?>
        </section>
    </div>
    <?php
}
get_footer();