<?php
//Template Name: Images
get_header();
while(have_posts()) {
	the_post();
	?>
	<div class="page">
		<?php
		$themeService = new ThemeService();
		$apiClient = new ApiClient();
		$themeService->loadSliderTemplate();
		$themeService->loadBreadcrumbTemplate();
		?>
		<section class="page__content">
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
			<div class="page__galleries">
				<?php
				$galleries = $apiClient->getGalleries();
				foreach($galleries as $gallery) {
					?>
					<a href="<?php echo $gallery['link']; ?>" class="page__gallery-link" style="background-image: url('<?php echo $gallery['image']; ?>')">
						<span class="page__gallery-title"><?php echo $gallery['title']; ?></span>
					</a>
					<?php
				}
				?>
			</div>
		</section>
	</div>
	<?php
}
get_footer();