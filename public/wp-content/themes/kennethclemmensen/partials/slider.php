<?php
use KCSlider\Includes\KCSlider;
use KCSlider\Includes\KCSliderSettings;
$kcSlider = new KCSlider();
$kcSliderSettings = new KCSliderSettings();
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
        ?>
        <img src="<?php echo $kcSlider->getSlideImage(); ?>" alt="<?php the_title(); ?>" class="slider__image">
        <?php
    }
    wp_reset_query();
    ?>
</div>