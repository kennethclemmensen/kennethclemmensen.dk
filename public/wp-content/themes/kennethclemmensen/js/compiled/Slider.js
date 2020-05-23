/**
 * The Slider class contains methods to handle the functionality of the slider
 */
export class Slider {
    /**
     * Initialize a new instance of the Slider class
     */
    constructor() {
        this.slides = document.getElementsByClassName('slider__slide');
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
        let sliderImage = document.getElementById('slider-image');
        let randomNumber = this.getRandomNumber();
        let name = 'data-slide-image';
        let backgroundImageUrl = this.slides[randomNumber].getAttribute(name);
        if (!sliderImage || !backgroundImageUrl)
            return;
        this.setBackgroundImage(sliderImage, backgroundImageUrl);
        setInterval(() => {
            if (!sliderImage)
                return;
            sliderImage.animate([{ opacity: 1 }, { opacity: 0 }], {
                duration: delay
            }).onfinish = () => {
                randomNumber = this.getRandomNumber();
                backgroundImageUrl = this.slides[randomNumber].getAttribute(name);
                if (!sliderImage || !backgroundImageUrl)
                    return;
                this.setBackgroundImage(sliderImage, backgroundImageUrl);
                sliderImage.animate([{ opacity: 0 }, { opacity: 1 }], {
                    duration: delay
                });
            };
        }, duration);
    }
    /**
     * Set a background image on an element
     *
     * @param element the element to set the background image on
     * @param backgroundImageUrl the background image url
     */
    setBackgroundImage(element, backgroundImageUrl) {
        element.style.backgroundImage = 'url("' + backgroundImageUrl + '")';
    }
}
