<?php
$slider = ThemeSettings::getInstance();
?>
<div id="slider" class="slider" data-delay="<?php echo $slider->getDelay(); ?>" data-duration="<?php echo $slider->getDuration(); ?>">
    <?php
    $slides = ThemeHelper::getSlides();
    foreach($slides as $slide) {
        echo '<div class="slider__image" style="background-image: url('.$slide['image'].')"></div>';
    }
    ?>
</div>