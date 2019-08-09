/**
 * The Slider class contains methods to handle the functionality of the slider
 */
export default class Slider {
    /**
     * Slider constructor
     *
     * @param delay the delay of a slide
     * @param duration the duration of a slide
     */
    constructor(delay, duration) {
        this.delay = delay;
        this.duration = duration;
        this.sliderImages = $('.slider__image');
        this.showSlider();
    }
    /**
     * Get a random number
     *
     * @returns {number} a random number
     */
    getRandomNumber() {
        return Math.floor(Math.random() * this.sliderImages.length);
    }
    /**
     * Show the slider
     */
    showSlider() {
        let randomNumber = this.getRandomNumber();
        this.sliderImages.eq(randomNumber).show();
        setInterval(() => {
            this.sliderImages.eq(randomNumber).fadeOut(this.duration, () => {
                this.sliderImages.eq(randomNumber).hide(); //prevent display block on more than one image
                randomNumber = this.getRandomNumber();
                this.sliderImages.eq(randomNumber).fadeIn(this.duration);
            });
        }, this.delay);
    }
}
