/**
 * The Slider class contains methods to handle the functionality of the slider
 */
export class Slider {
    /**
     * Slider constructor
     *
     * @param delay the delay between two slides
     * @param duration the duration of a slide
     */
    constructor(delay, duration) {
        this.delay = delay;
        this.duration = duration;
        this.sliderImages = jQuery('.slider__image');
        this.currentRandomNumber = -1;
        this.showSlider();
    }
    /**
     * Get a random number between 0 and the number of slides minus 1
     *
     * @returns {number} a random number
     */
    getRandomNumber() {
        let randomNumber = Math.floor(Math.random() * this.sliderImages.length);
        if (this.currentRandomNumber === randomNumber)
            return this.getRandomNumber();
        this.currentRandomNumber = randomNumber;
        return this.currentRandomNumber;
    }
    /**
     * Show the slider
     */
    showSlider() {
        let randomNumber = this.getRandomNumber();
        this.sliderImages.eq(randomNumber).show();
        setInterval(() => {
            this.sliderImages.eq(randomNumber).fadeOut(this.delay, () => {
                randomNumber = this.getRandomNumber();
                this.sliderImages.eq(randomNumber).fadeIn(this.delay);
            });
        }, this.duration);
    }
}
