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
        $previousText = TranslationStrings::getTranslatedString(TranslationStrings::PREVIOUS);
        $nextText = TranslationStrings::getTranslatedString(TranslationStrings::NEXT);
        $downloadsText = TranslationStrings::getTranslatedString(TranslationStrings::NUMBER_OF_DOWNLOADS);
        ?>
        <section class="page__content">
            <h1><?php the_title(); ?></h1>
            <?php the_content(); ?>
            <div id="files-app">
                <files file-types="<?php echo ThemeHelper::getFileTypes(); ?>"
                    per-page="<?php echo ThemeSettings::getInstance()->getFilesPerPage(); ?>"
                    previous-text="<?php echo $previousText; ?>"
                    next-text="<?php echo $nextText; ?>" 
                    number-of-downloads-text="<?php echo $downloadsText; ?>"></files>
            </div>
        </section>
    </div>
    <?php
}
get_footer();