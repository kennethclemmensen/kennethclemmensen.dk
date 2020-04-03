/**
 * The Slider class contains methods to handle the functionality of the slider
 */
export class Slider {
    /**
     * Initialize a new instance of the Slider class
     */
    constructor() {
        this.sliderImages = $('.slider__image');
        this.currentRandomNumber = -1;
    }
    /**
     * Get a random number between 0 and the number of slides minus 1
     *
     * @returns a random number
     */
    getRandomNumber() {
        let randomNumber = Math.floor(Math.random() * this.sliderImages.length);
        if (this.currentRandomNumber === randomNumber)
            return this.getRandomNumber();
        this.currentRandomNumber = randomNumber;
        return this.currentRandomNumber;
    }
    /**
     * Show the slides
     *
     * @param delay the delay between two slides
     * @param duration the duration of a slide
     */
    showSlides(delay, duration) {
        let randomNumber = this.getRandomNumber();
        this.sliderImages.eq(randomNumber).show();
        setInterval(() => {
            this.sliderImages.eq(randomNumber).fadeOut(delay, () => {
                randomNumber = this.getRandomNumber();
                this.sliderImages.eq(randomNumber).fadeIn(delay);
            });
        }, duration);
    }
}
