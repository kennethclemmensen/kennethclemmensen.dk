<?php
get_header();
?>
<div class="page">
    <?php
    ThemeHelper::loadSliderTemplate();
    ThemeHelper::loadBreadcrumbTemplate();
    ?>
    <section class="page__content">
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
        <div class="gallery">
            <?php
            $perPage = ThemeSettings::getInstance()->getImagesPerPage();
            $pagination = new Pagination(ApiClient::getImages(), $perPage);
            $images = $pagination->getItems();
            foreach($images as $image) {
                $url = $image['url'];
                $title = $image['title'];
                $gallery = $image['gallery'];
                $thumbnail = $image['thumbnail'];
                $width = $image['width'];
                $height = $image['height'];
                ?>
                <a href="<?php echo $url; ?>" data-title="<?php echo $title; ?>" data-lightbox="<?php echo $gallery; ?>" class="gallery__link">
                    <img src="<?php echo $thumbnail; ?>" alt="<?php echo $title; ?>" class="gallery__image" width="<?php echo $width; ?>" height="<?php echo $height; ?>">
                </a>
                <?php
            }
            ?>
            <div class="pagination">
                <?php echo $pagination->getPaginationLinks(TranslationStrings::getPreviousText(), TranslationStrings::getNextText()); ?>
            </div>
        </div>
    </section>
</div>
<?php
get_footer();