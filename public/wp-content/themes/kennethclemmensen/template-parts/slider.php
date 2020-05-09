<?php
$slider = ThemeSettings::getInstance();
?>
<div id="slider" class="slider" data-delay="<?php echo $slider->getDelay(); ?>" data-duration="<?php echo $slider->getDuration(); ?>">
    <?php
    $slides = ApiClient::getSlides();
    foreach($slides as $slide) {
        echo '<div class="slider__slide" data-slide-image="'.$slide['image'].'"></div>';
    }
    ?>
    <div class="slider__image"></div>
</div>