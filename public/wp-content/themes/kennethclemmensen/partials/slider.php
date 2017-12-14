<?php
use KCSlider\Includes\KCSlider;
use KCSlider\Includes\KCSliderSettings;
$kc_slider = new KCSlider();
$kc_slider_settings = new KCSliderSettings();
$delay = $kc_slider_settings->getDelay();
$duration = $kc_slider_settings->getDuration();
?>
<div id="slider" class="slider" data-delay="<?php echo $delay; ?>" data-duration="<?php echo $duration; ?>">
    <?php
    $args = [
        'post_type' => KCSlider::SLIDES,
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'menu_order'
    ];
    $wp_query = new WP_Query($args);
    while($wp_query->have_posts()) {
        $wp_query->the_post();
        ?>
        <img src="<?php echo $kc_slider->getSlideImage(); ?>" alt="<?php the_title(); ?>" class="slider__image">
        <?php
    }
    wp_reset_query();
    ?>
</div>