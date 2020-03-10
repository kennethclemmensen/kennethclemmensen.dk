<?php
use KCSlider\Includes\KCSlider;
use KCSlider\Includes\KCSliderSettings;
$kcSlider = new KCSlider();
$kcSliderSettings = KCSliderSettings::getInstance();
$delay = $kcSliderSettings->getDelay();
$duration = $kcSliderSettings->getDuration();
?>
<div id="slider" class="slider" data-delay="<?php echo $delay; ?>" data-duration="<?php echo $duration; ?>">
    <?php
    $args = [
        'post_type' => KCSlider::SLIDES,
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'menu_order'
    ];
    $wpQuery = new WP_Query($args);
    while($wpQuery->have_posts()) {
        $wpQuery->the_post();
        echo '<div class="slider__image" style="background-image: url('.$kcSlider->getSlideImageUrl(get_the_ID()).')"></div>';
    }
    wp_reset_query();
    ?>
</div>