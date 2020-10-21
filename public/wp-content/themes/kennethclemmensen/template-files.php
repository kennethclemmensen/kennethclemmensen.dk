<?php
//Template Name: Files
get_header();
while(have_posts()) {
    the_post();
    ?>
    <div class="page">
        <?php
        ThemeHelper::loadSliderTemplate();
        ThemeHelper::loadBreadcrumbTemplate();
        ?>
        <section class="page__content">
            <h1><?php the_title(); ?></h1>
            <?php the_content(); ?>
            <div id="files-app">
                <files file-types="<?php echo ThemeHelper::getFileTypes(); ?>"
                    per-page="<?php echo ThemeSettings::getInstance()->getFilesPerPage(); ?>"
                    previous-text="<?php echo TranslationStrings::getPreviousText(); ?>"
                    next-text="<?php echo TranslationStrings::getNextText(); ?>" 
                    number-of-downloads-text="<?php echo TranslationStrings::getNumberOfDownloadsText(); ?>"></files>
            </div>
        </section>
    </div>
    <?php
}
get_footer();