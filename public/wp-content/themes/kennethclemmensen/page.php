<?php
get_header();
while(have_posts()) {
	the_post();
	?>
	<div class="page">
		<?php
		ThemeHelper::loadSliderTemplate();
		ThemeHelper::loadBreadcrumbTemplate();
		?>
		<section class="page__content">
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</section>
	</div>
	<?php
}
get_footer();