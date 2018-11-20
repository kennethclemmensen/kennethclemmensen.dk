<?php
//Template Name: Images
use KCGallery\Includes\KCGallery;

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
            $kcGallery = new KCGallery();
            $galleries = $kcGallery->getGalleries();
            ?>
            <div class="galleries">
                <?php
                foreach($galleries as $key => $gallery) {
                    $src = $kcGallery->getGalleryPhotoUrl($key);
                    ?>
                    <div class="galleries__gallery">
                        <a href="<?php echo get_permalink($key); ?>">
                            <img src="<?php echo $src; ?>" alt="<?php echo get_the_title($key); ?>">
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
            ?>
        </section>
    </div>
    <?php
}
get_footer();