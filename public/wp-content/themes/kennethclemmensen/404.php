<?php
get_header();
$themeService = new ThemeService();
?>
	<div class="page">
		<?php $themeService->loadSliderTemplate(); ?>
		<section class="page__content">
			<?php dynamic_sidebar($themeService->getPageNotFoundSidebarID()); ?>
		</section>
	</div>
<?php
get_footer();