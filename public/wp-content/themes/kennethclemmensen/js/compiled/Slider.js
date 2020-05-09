/**
 * The Slider class contains methods to handle the functionality of the slider
 */
export class Slider {
    /**
     * Initialize a new instance of the Slider class
     */
    constructor() {
        this.slides = $('.slider__slide');
        this.currentRandomNumber = -1;
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
     * Show the slides
     *
     * @param delay the delay between two slides
     * @param duration the duration of a slide
     */
    showSlides(delay, duration) {
        let randomNumber = this.getRandomNumber();
        let sliderImage = $('.slider__image');
        let key = 'slide-image';
        this.setBackgroundImage(sliderImage, this.slides.eq(randomNumber).data(key));
        sliderImage.show();
        setInterval(() => {
            sliderImage.fadeOut(delay, () => {
                randomNumber = this.getRandomNumber();
                this.setBackgroundImage(sliderImage, this.slides.eq(randomNumber).data(key));
                sliderImage.fadeIn(delay);
            });
        }, duration);
    }
    /**
     * Set a background image on an element
     *
     * @param element the element to set the background image on
     * @param backgroundImageUrl the background image url
     */
    setBackgroundImage(element, backgroundImageUrl) {
        element.css('background-image', 'url(' + backgroundImageUrl + ')');
    }
}
