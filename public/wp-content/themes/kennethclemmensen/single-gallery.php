<?php
get_header();
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
            $perPage = ThemeSettings::getInstance()->getImagesPerPage();
            $pagination = new Pagination(ApiClient::getImages(), $perPage);
            $images = $pagination->getItems();
            foreach($images as $image) {
                $title = $image['title'];
                ?>
                <a href="<?php echo $image['url']; ?>" data-title="<?php echo $title; ?>" data-lightbox="<?php echo $image['gallery']; ?>" class="gallery__link">
                    <img src="<?php echo $image['thumbnail']; ?>" alt="<?php echo $title; ?>" class="gallery__image">
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