<?php
get_header();
$themeService = new ThemeService();
$apiClient = new ApiClient();
$translationStrings = new TranslationStrings();
$perPage = ThemeSettings::getInstance()->getImagesPerPage();
$pagination = new Pagination($apiClient->getImages(), $perPage);
$images = $pagination->getItems();
$previousText = $translationStrings->getTranslatedString(TranslationStrings::PREVIOUS);
$nextText = $translationStrings->getTranslatedString(TranslationStrings::NEXT);				
?>
<div class="page">
	<?php
	$themeService->loadSliderTemplate();
	$themeService->loadBreadcrumbTemplate();
	?>
	<section class="page__content">
		<h1><?php the_title(); ?></h1>
		<?php the_content(); ?>
		<div class="page__gallery">
			<?php
			foreach($images as $image) {
				$url = $image['url'];
				$title = $image['title'];
				$thumbnail = $image['thumbnail'];
				$width = $image['width'];
				$height = $image['height'];
				?>
				<a href="<?php echo $url; ?>" data-title="<?php echo $title; ?>" class="page__gallery-thumbnail-link">
					<img src="<?php echo $thumbnail; ?>" alt="<?php echo $title; ?>" class="page__gallery-thumbnail" width="<?php echo $width; ?>" height="<?php echo $height; ?>">
				</a>
				<?php
			}
			?>
			<div class="pagination">
				<?php
				echo $pagination->getPaginationLinks($previousText, $nextText);
				?>
			</div>
		</div>
	</section>
</div>
<?php
get_footer();