import { interval, tap } from 'rxjs';
import { SliderAnimation } from './SliderAnimation';
/**
 * The Slider class contains methods to handle the functionality of the slider
 */
export class Slider {
    #slides;
    #sliderImage;
    #currentRandomNumber;
    #delay;
    #duration;
    #animation;
    /**
     * Initialize a new instance of the Slider class
     */
    constructor() {
        const slider = document.getElementById('slider');
        const dataset = slider?.dataset;
        const defaultDelay = 500;
        const defaultDuration = 8000;
        this.#slides = document.getElementsByClassName('slider__slide');
        this.#sliderImage = document.getElementById('slider-image');
        this.#currentRandomNumber = -1;
        this.#delay = (dataset?.delay != null) ? parseInt(dataset.delay) : defaultDelay;
        this.#duration = (dataset?.duration != null) ? parseInt(dataset.duration) : defaultDuration;
        this.#animation = (dataset?.animation ?? SliderAnimation.Fade);
    }
    /**
     * Show the slides
     */
    showSlides() {
        let randomNumber = this.getRandomNumber();
        const name = 'data-slide-image';
        let backgroundImageUrl = this.#slides[randomNumber]?.getAttribute(name);
        const startKeyframes = this.getStartKeyframes(this.#animation);
        const endKeyframes = this.getEndKeyframes(this.#animation);
        if (backgroundImageUrl != null) {
            this.setBackgroundImage(backgroundImageUrl);
            interval(this.#duration).pipe(tap(() => {
                if (this.#sliderImage) {
                    this.#sliderImage.animate(startKeyframes, {
                        duration: this.#delay
                    }).onfinish = () => {
                        randomNumber = this.getRandomNumber();
                        backgroundImageUrl = this.#slides[randomNumber]?.getAttribute(name);
                        if (backgroundImageUrl != null && this.#sliderImage != null) {
                            this.setBackgroundImage(backgroundImageUrl);
                            this.#sliderImage.animate(endKeyframes, { duration: this.#delay });
                        }
                    };
                }
            })).subscribe();
        }
    }
    /**
     * Get a random number between 0 and the number of slides minus 1
     *
     * @returns a random number
     */
    getRandomNumber() {
        const randomNumber = Math.floor(Math.random() * this.#slides.length);
        if (this.#currentRandomNumber === randomNumber)
            return this.getRandomNumber();
        this.#currentRandomNumber = randomNumber;
        return this.#currentRandomNumber;
    }
    /**
     * Set a background image on the slider image
     *
     * @param backgroundImageUrl the background image url
     */
    setBackgroundImage(backgroundImageUrl) {
        if (this.#sliderImage != null) {
            this.#sliderImage.style.backgroundImage = 'url("' + backgroundImageUrl + '")';
        }
    }
    /**
     * Get the start keyframes based on the animation
     *
     * @param animation the animation
     * @return the start keyframes
     */
    getStartKeyframes(animation) {
        let startKeyframes = [];
        if (this.#sliderImage != null) {
            const width = this.#sliderImage.clientWidth;
            const height = this.#sliderImage.clientHeight;
            const px = 'px';
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
        if (this.#sliderImage) {
            const width = this.#sliderImage.clientWidth;
            const height = this.#sliderImage.clientHeight;
            const px = 'px';
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
