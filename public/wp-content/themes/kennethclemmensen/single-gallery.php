<?php
use KCGallery\Includes\KCGallery;

get_header();
while(have_posts()) {
    the_post();
    ?>
    <div class="page">
        <?php
        ThemeHelper::loadSliderTemplatePart();
        ThemeHelper::loadBreadcrumbTemplatePart();
        ?>
        <section class="page__content">
            <h1><?php the_title(); ?></h1>
            <?php the_content(); ?>
            <div class="gallery">
                <?php
                $kcGallery = new KCGallery();
                $themeSettings = ThemeSettings::getInstance();
                $galleryID = get_the_ID();
                $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
                $args = [
                    'post_type' => KCGallery::PHOTO,
                    'posts_per_page' => $themeSettings->getPhotosPerPage(),
                    'order' => 'ASC',
                    'orderby' => 'title',
                    'meta_key' => $kcGallery->getPhotoGalleryFieldID(),
                    'meta_value' => $galleryID,
                    'paged' => $paged
                ];
                $wpQuery = new WP_Query($args);
                while($wpQuery->have_posts()) {
                    $wpQuery->the_post();
                    $title = get_the_title();
                    $url = $kcGallery->getPhotoUrl($id);
                    $thumbnail = $kcGallery->getPhotoThumbnailUrl($id);
                    ?>
                    <a href="<?php echo $url; ?>" data-title="<?php echo $title; ?>"
                       data-lightbox="<?php echo $galleryID; ?>" class="gallery__link">
                        <img src="<?php echo $thumbnail; ?>" alt="<?php echo $title; ?>" class="gallery__photo">
                    </a>
                    <?php
                }
                ?>
                <div class="gallery__pagination">
                    <?php
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
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </section>
    </div>
    <?php
}
get_footer();