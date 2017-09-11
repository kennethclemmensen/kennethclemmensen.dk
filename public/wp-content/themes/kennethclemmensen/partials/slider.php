<div id="slider" class="slider">
	<?php
    use KCSlider\Includes\KC_Slider;
	$kc_slider = new KC_Slider();
	$args = [
		'post_type' => KC_Slider::SLIDES,
		'posts_per_page' => -1
	];
	$wp_query = new WP_Query($args);
	while($wp_query->have_posts()) {
		$wp_query->the_post();
		?>
        <img src="<?php echo $kc_slider->get_slide_image(); ?>" alt="<?php the_title(); ?>" class="slider__image">
		<?php
	}
	wp_reset_query();
	?>
</div>
