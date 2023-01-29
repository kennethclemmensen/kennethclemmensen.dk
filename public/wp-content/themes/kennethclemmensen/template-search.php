<?php
//Template Name: Search
get_header();
while(have_posts()) {
	the_post();
	?>
	<div class="page">
		<?php
		$themeService = new ThemeService();
		$translationStrings = new TranslationStrings();
		$themeService->loadSliderTemplate();
		$themeService->loadBreadcrumbTemplate();
		?>
		<section class="page__content">
			<h1><?php the_title(); ?></h1>
			<?php
			the_content();
			$searchText = $translationStrings->getTranslatedString(TranslationStrings::SEARCH);
			$previousText = $translationStrings->getTranslatedString(TranslationStrings::PREVIOUS);
			$nextText = $translationStrings->getTranslatedString(TranslationStrings::NEXT);
			?>
			<div id="search-app">
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" @submit.prevent="search">
					<input type="search" name="search" placeholder="<?php echo $searchText; ?>" v-model="searchString" required>
					<input type="submit" value="<?php echo $searchText; ?>">
				</form>
				<h2 v-if="results.length === 0 && searchString !== ''">
					<?php echo $translationStrings->getTranslatedString(TranslationStrings::NO_RESULTS); ?>
				</h2>
				<div v-else-if="results.length > 0">
					<h2><?php echo $translationStrings->getTranslatedString(TranslationStrings::SEARCH_RESULTS); ?></h2>
					<search-results :results="results" per-page="<?php echo ThemeSettings::getInstance()->getSearchResultsPerPage(); ?>"
									previous-text="<?php echo $previousText; ?>"
									next-text="<?php echo $nextText; ?>"></search-results>
				</div>
			</div>
		</section>
	</div>
	<?php
}
get_footer();