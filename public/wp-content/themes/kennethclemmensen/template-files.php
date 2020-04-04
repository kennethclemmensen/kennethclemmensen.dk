<?php
//Template Name: Files
use KC\Core\CustomPostType;
use KC\Files\Files;
use KC\Utils\PluginHelper;

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
            <?php
            the_content();
            $paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
            $terms = (strpos($_SERVER['REQUEST_URI'], 'php') === false) ? 5 : 4;
            $args = [
                'post_type' => CustomPostType::FILE,
                'posts_per_page' => 7,
                'order' => 'ASC',
                'tax_query' => [
                    [
                        'taxonomy' => PluginHelper::getFileTypeTaxonomyName(),
                        'terms' => $terms
                    ]
                ],
                'paged' => $paged
            ];
            $wpQuery = new WP_Query($args);
            $files = new Files();
            while($wpQuery->have_posts()) {
                $wpQuery->the_post();
                $id = get_the_ID();
                $fileUrl = $files->getFileUrl($id);
                $fileName = $files->getFileName($id);
                $fileDescription = $files->getFileDescription($id);
                $fileDownloads = PluginHelper::getFileDownloads($id);
                ?>
                <div>
                    <a href="<?php echo $fileUrl; ?>" class="kc-file-download-link" rel="nofollow" data-file-id="<?php echo $id; ?>" download>
                        <?php echo $fileName; ?>
                    </a>
                    <p><?php echo $fileDescription; ?></p>
                    <p>
                        <?php echo TranslationStrings::getNumberOfDownloadsText().' '; ?>
                        <span class="kc-file-downloads"><?php echo $fileDownloads; ?></span>
                    </p>
                </div>
            <?php
            }
            $big = 999999999; // need an unlikely integer
            $replace = '%#%';
            $links = paginate_links([
                'base' => str_replace($big, $replace, esc_url(get_pagenum_link($big))),
                'format' => '?paged='.$replace,
                'current' => max(1, $paged),
                'total' => $wpQuery->max_num_pages,
                'prev_text' => TranslationStrings::getPreviousText(),
                'next_text' => TranslationStrings::getNextText()
            ]);
            ?>
            <div class="pagination">
                <?php echo $links; ?>
            </div>
        </section>
    </div>
    <?php
}
get_footer();