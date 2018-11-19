<?php
get_header();
while(have_posts()) {
    the_post();
    ?>
    <div class="page">
        <?php
        get_template_part('partials/slider');
        get_template_part('partials/breadcrumb');
        ?>
        <section class="page__content">
            <h1><?php the_title(); ?></h1>
            <?php
            the_content();
            echo do_shortcode('[gallery id='.get_the_ID().']');
            ?>
        </section>
    </div>
    <?php
}
get_footer();