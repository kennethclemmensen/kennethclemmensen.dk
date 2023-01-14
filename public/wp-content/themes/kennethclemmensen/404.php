<?php
get_header();
?>
	<div class="page">
		<?php ThemeService::loadSliderTemplate(); ?>
		<section class="page__content">
			<?php dynamic_sidebar(ThemeService::getPageNotFoundSidebarID()); ?>
		</section>
	</div>
<?php
get_footer();