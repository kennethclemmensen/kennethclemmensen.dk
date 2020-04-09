<?php
//Template Name: Files
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
            <div id="files-app" data-type="<?php echo ThemeHelper::getFileTypes(); ?>">
                <files :files="files"
                    previous-text="<?php echo TranslationStrings::getPreviousText(); ?>"
                    next-text="<?php echo TranslationStrings::getNextText(); ?>" 
                    number-of-downloads-text="<?php echo TranslationStrings::getNumberOfDownloadsText(); ?>"></files>
            </div>
        </section>
    </div>
    <?php
}
get_footer();