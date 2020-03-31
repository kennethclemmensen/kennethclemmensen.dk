<?php
use KC\Core\CustomPostType;
use KC\Slider\Slider;
use KC\Slider\SliderSettings;
$slider = new Slider();
$sliderSettings = SliderSettings::getInstance();
$delay = $sliderSettings->getDelay();
$duration = $sliderSettings->getDuration();
?>
<div id="slider" class="slider" data-delay="<?php echo $delay; ?>" data-duration="<?php echo $duration; ?>">
    <?php
    $args = [
        'post_type' => CustomPostType::SLIDES,
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'menu_order'
    ];
    $wpQuery = new WP_Query($args);
    while($wpQuery->have_posts()) {
        $wpQuery->the_post();
        echo '<div class="slider__image" style="background-image: url('.$slider->getSlideImageUrl(get_the_ID()).')"></div>';
    }
    wp_reset_query();
    ?>
</div>