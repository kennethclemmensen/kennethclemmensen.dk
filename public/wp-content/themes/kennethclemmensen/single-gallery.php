<?php
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
            <?php the_content(); ?>
            <div class="gallery">
                <?php
                $kcGallery = new KCGallery();
                $galleryID = get_the_ID();
                $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
                $args = [
                    'post_type' => KCGallery::PHOTO,
                    'posts_per_page' => 39,
                    'order' => 'ASC',
                    'meta_key' => $kcGallery->getPhotoGalleryFieldID(),
                    'meta_value' => $galleryID,
                    'paged' => $paged
                ];
                $wpQuery = new WP_Query($args);
                while($wpQuery->have_posts()) {
                    $wpQuery->the_post();
                    $title = get_the_title();
                    echo '<a href="'.$kcGallery->getPhotoUrl($id).'" data-title="'.$title.'" data-lightbox="'.$galleryID.'">';
                    echo '<img src="'.$kcGallery->getPhotoThumbnailUrl($id).'" class="gallery__photo" alt="'.$title.'"></a>';
                }
                echo '<div class="gallery__pagination">';
                $big = 999999999; // need an unlikely integer
                $replace = '%#%';
                echo paginate_links([
                    'base' => str_replace($big, $replace, esc_url(get_pagenum_link($big))),
                    'format' => '?paged='.$replace,
                    'current' => max(1, $paged),
                    'total' => $wpQuery->max_num_pages,
                    'prev_text' => TranslationStrings::getPreviousText(),
                    'next_text' => TranslationStrings::getNextText()
                ]);
                echo '</div></div>';
                wp_reset_postdata();
                ?>
            </div>
        </section>
    </div>
    <?php
}
get_footer();