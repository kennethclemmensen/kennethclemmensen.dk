<?php
//Template Name: Search
get_header();
while(have_posts()) {
    the_post();
    ?>
    <div class="page">
        <?php
        get_template_part('template-parts/slider');
        get_template_part('template-parts/breadcrumb');
        ?>
        <section class="page__content">
            <h1><?php the_title(); ?></h1>
            <?php
            the_content();
            $searchText = TranslationStrings::getSearchText();
            ?>
            <div id="search-app">
                <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" @submit.prevent="search">
                    <input type="search" name="search" placeholder="<?php echo $searchText; ?>" v-model="searchString" required>
                    <input type="submit" value="<?php echo $searchText; ?>">
                </form>
                <h2 v-if="results.length === 0 && searchString !== ''">
                    <?php echo TranslationStrings::getNoResultsText(); ?>
                </h2>
                <div v-else-if="results.length > 0">
                    <h2><?php echo TranslationStrings::getSearchResultsText(); ?></h2>
                    <search-results :results="results"
                                    previous-text="<?php echo TranslationStrings::getPreviousText(); ?>"
                                    next-text="<?php echo TranslationStrings::getNextText(); ?>"></search-results>
                </div>
            </div>
        </section>
    </div>
    <?php
}
get_footer();