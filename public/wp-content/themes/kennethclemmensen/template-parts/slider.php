<?php
$slider = json_decode(file_get_contents($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/wp-json/kcapi/v1/slider'));
?>
<div id="slider" class="slider" data-delay="<?php echo $slider->delay; ?>" data-duration="<?php echo $slider->duration; ?>">
    <?php
    $slidesImages = $slider->slidesImages;
    foreach($slidesImages as $slideImage) {
        echo '<div class="slider__image" style="background-image: url('.$slideImage.')"></div>';
    }    
    ?>
</div>