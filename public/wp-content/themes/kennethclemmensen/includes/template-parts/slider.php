<?php
$themeSettings = ThemeSettings::getInstance();
$delay = $themeSettings->getSliderDelay();
$duration = $themeSettings->getSliderDuration();
$animation = $themeSettings->getSliderAnimation();
$apiClient = new ApiClient();
$slides = $apiClient->getSlides();
?>
<div id="slider" class="slider" data-delay="<?php echo $delay; ?>" 
	data-duration="<?php echo $duration; ?>" data-animation="<?php echo $animation; ?>">
	<?php
	foreach($slides as $slide) {
		echo '<div class="slider__slide" data-slide-image="'.$slide['image'].'"></div>';
	}
	?>
	<div id="slider-image" class="slider__image"></div>
</div>