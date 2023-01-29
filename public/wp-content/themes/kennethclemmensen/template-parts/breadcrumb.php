<div class="breadcrumb">
	<span class="breadcrumb__title">
		<?php
		$themeService = new ThemeService();
		$translationStrings = new TranslationStrings();
		echo $translationStrings->getTranslatedString(TranslationStrings::YOU_ARE_HERE);
		?>
	</span>
	<ul class="breadcrumb__list">
		<?php
		$pages = $themeService->getBreadcrumb();
		$count = count($pages);
		for($i = 0; $i < $count; $i++) {
			$pageID = $pages[$i];
			$title = ($i === 0) ? $translationStrings->getTranslatedString(TranslationStrings::FRONT_PAGE) : get_the_title($pageID);
			?>
			<li class="breadcrumb__list-item">
				<?php
				echo (($i + 1) === $count) ? '<em>'.$title.'</em>' : '<a href="'.get_permalink($pageID).'">'.$title.'</a>';
				?>
			</li>
			<?php
		}
		?>
	</ul>
</div>