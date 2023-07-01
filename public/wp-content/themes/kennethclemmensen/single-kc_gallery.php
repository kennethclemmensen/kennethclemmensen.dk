<?php
get_header();
?>
<div class="page">
	<?php
	$themeService = new ThemeService();
	$apiClient = new ApiClient();
	$translationStrings = new TranslationStrings();
	$themeService->loadSliderTemplate();
	$themeService->loadBreadcrumbTemplate();
	?>
	<section class="page__content">
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
		<div class="page__gallery">
			<?php
			$perPage = ThemeSettings::getInstance()->getImagesPerPage();
			$pagination = new Pagination($apiClient->getImages(), $perPage);
			$images = $pagination->getItems();
			for($i = 0; $i < count($images); $i++) {
				$image = $images[$i];
				$url = $image['url'];
				$title = $image['title'];
				$thumbnail = $image['thumbnail'];
				$width = $image['width'];
				$height = $image['height'];
				?>
				<a href="<?php echo $url; ?>" data-title="<?php echo $title; ?>" data-index="<?php echo $i; ?>" class="page__gallery-thumbnail-link">
					<img src="<?php echo $thumbnail; ?>" alt="<?php echo $title; ?>" class="page__gallery-thumbnail" width="<?php echo $width; ?>" height="<?php echo $height; ?>">
				</a>
				<?php
			}
			?>
			<div class="pagination">
				<?php
				$previousText = $translationStrings->getTranslatedString(TranslationStrings::PREVIOUS);
				$nextText = $translationStrings->getTranslatedString(TranslationStrings::NEXT);
				echo $pagination->getPaginationLinks($previousText, $nextText);
				?>
			</div>
		</div>
	</section>
</div>
<?php
get_footer();