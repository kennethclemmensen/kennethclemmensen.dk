<?php
$slider = ThemeSettings::getInstance();
$delay = $slider->getDelay();
$duration = $slider->getDuration();
$animation = $slider->getAnimation();
?>
<div id="slider" class="slider" data-delay="<?php echo $delay; ?>" 
    data-duration="<?php echo $duration; ?>" data-animation="<?php echo $animation; ?>">
    <?php
    $slides = ApiClient::getSlides();
    foreach($slides as $slide) {
        echo '<div class="slider__slide" data-slide-image="'.$slide['image'].'"></div>';
    }
    ?>
    <div id="slider-image" class="slider__image"></div>
</div>