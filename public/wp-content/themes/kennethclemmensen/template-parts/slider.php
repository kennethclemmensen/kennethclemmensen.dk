<?php
$slider = ThemeHelper::getSlider();
?>
<div id="slider" class="slider" data-delay="<?php echo $slider->delay; ?>" data-duration="<?php echo $slider->duration; ?>">
    <?php
    $slidesImages = $slider->slidesImages;
    foreach($slidesImages as $slideImage) {
        echo '<div class="slider__image" style="background-image: url('.$slideImage.')"></div>';
    }
    ?>
</div>