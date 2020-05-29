import { SliderAnimation } from './enums/SliderAnimation';
/**
 * The Slider class contains methods to handle the functionality of the slider
 */
export class Slider {
    /**
     * Initialize a new instance of the Slider class
     */
    constructor() {
        this.slides = document.getElementsByClassName('slider__slide');
        this.sliderImage = document.getElementById('slider-image');
        this.currentRandomNumber = -1;
    }
    /**
     * Show the slides
     *
     * @param delay the delay between two slides
     * @param duration the duration of a slide
     * @param animation the animation for the slides
     */
    showSlides(delay, duration, animation) {
        let randomNumber = this.getRandomNumber();
        let name = 'data-slide-image';
        let backgroundImageUrl = this.slides[randomNumber].getAttribute(name);
        if (!backgroundImageUrl)
            return;
        this.setBackgroundImage(backgroundImageUrl);
        let startKeyframes = this.getStartKeyframes(animation);
        let endKeyframes = this.getEndKeyframes(animation);
        setInterval(() => {
            if (this.sliderImage) {
                this.sliderImage.animate(startKeyframes, {
                    duration: delay
                }).onfinish = () => {
                    randomNumber = this.getRandomNumber();
                    backgroundImageUrl = this.slides[randomNumber].getAttribute(name);
                    if (this.sliderImage && backgroundImageUrl) {
                        this.setBackgroundImage(backgroundImageUrl);
                        this.sliderImage.animate(endKeyframes, {
                            duration: delay
                        });
                    }
                };
            }
        }, duration);
    }
    /**
     * Get a random number between 0 and the number of slides minus 1
     *
     * @returns a random number
     */
    getRandomNumber() {
        let randomNumber = Math.floor(Math.random() * this.slides.length);
        if (this.currentRandomNumber === randomNumber)
            return this.getRandomNumber();
        this.currentRandomNumber = randomNumber;
        return this.currentRandomNumber;
    }
    /**
     * Set a background image on the slider image
     *
     * @param backgroundImageUrl the background image url
     */
    setBackgroundImage(backgroundImageUrl) {
        if (this.sliderImage)
            this.sliderImage.style.backgroundImage = 'url("' + backgroundImageUrl + '")';
    }
    /**
     * Get the start keyframes based on the animation
     *
     * @param animation the animation
     * @return the start keyframes
     */
    getStartKeyframes(animation) {
        let startKeyframes = [];
        if (this.sliderImage) {
            let width = this.sliderImage.clientWidth;
            let height = this.sliderImage.clientHeight;
            let px = 'px';
            switch (animation) {
                case SliderAnimation.SlideDown:
                    startKeyframes = [{ backgroundPositionY: 0 }, { backgroundPositionY: height + px }];
                    break;
                case SliderAnimation.SlideLeft:
                    startKeyframes = [{ backgroundPositionX: 0 }, { backgroundPositionX: -width + px }];
                    break;
                case SliderAnimation.SlideRight:
                    startKeyframes = [{ backgroundPositionX: 0 }, { backgroundPositionX: width + px }];
                    break;
                case SliderAnimation.SlideUp:
                    startKeyframes = [{ backgroundPositionY: 0 }, { backgroundPositionY: -height + px }];
                    break;
                default:
                    startKeyframes = [{ opacity: 1 }, { opacity: 0 }];
                    break;
            }
        }
        return startKeyframes;
    }
    /**
     * Get the end keyframes based on the animation
     *
     * @param animation the animation
     * @return the end keyframes
     */
    getEndKeyframes(animation) {
        let endKeyframes = [];
        if (this.sliderImage) {
            let width = this.sliderImage.clientWidth;
            let height = this.sliderImage.clientHeight;
            let px = 'px';
            switch (animation) {
                case SliderAnimation.SlideDown:
                    endKeyframes = [{ backgroundPositionY: height + px }, { backgroundPositionY: 0 }];
                    break;
                case SliderAnimation.SlideLeft:
                    endKeyframes = [{ backgroundPositionX: -width + px }, { backgroundPositionX: 0 }];
                    break;
                case SliderAnimation.SlideRight:
                    endKeyframes = [{ backgroundPositionX: width + px }, { backgroundPositionX: 0 }];
                    break;
                case SliderAnimation.SlideUp:
                    endKeyframes = [{ backgroundPositionY: -height + px }, { backgroundPositionY: 0 }];
                    break;
                default:
                    endKeyframes = [{ opacity: 0 }, { opacity: 1 }];
                    break;
            }
        }
        return endKeyframes;
    }
}
