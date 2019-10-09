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
            <div class="page__galleries">
                <?php
                foreach($galleries as $id => $title) {
                    $src = $kcGallery->getGalleryPhotoUrl($id);
                    ?>
                    <a href="<?php echo get_permalink($id); ?>" class="page__gallery-link"
                       style="background-image: url('<?php echo $src; ?>')">
                        <?php echo $title; ?>
                    </a>
                    <?php
                }
                ?>
            </div>
        </section>
    </div>
    <?php
}
get_footer();