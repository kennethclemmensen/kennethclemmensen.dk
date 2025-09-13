<div class="breadcrumb">
	<span class="breadcrumb__title">
		<?php
		$themeService = new ThemeService();
		$translationStrings = new TranslationStrings();
		$pages = $themeService->getBreadcrumb();
		$numberOfPages = count($pages);
		$frontPage = $translationStrings->getTranslatedString(TranslationStrings::FRONT_PAGE);
		echo $translationStrings->getTranslatedString(TranslationStrings::YOU_ARE_HERE);
		?>
	</span>
	<ul class="breadcrumb__list">
		<?php
		for($i = 0; $i < $numberOfPages; $i++) {
			$pageId = $pages[$i];
			$title = ($i === 0) ? $frontPage : get_the_title($pageId);
			$isLastPage = ($i + 1) === $numberOfPages;
			$link = get_permalink($pageId);
			?>
			<li class="breadcrumb__list-item">
				<?php
				echo ($isLastPage) ? '<em>'.$title.'</em>' : '<a href="'.$link.'">'.$title.'</a>';
				?>
			</li>
			<?php
		}
		?>
	</ul>
</div>