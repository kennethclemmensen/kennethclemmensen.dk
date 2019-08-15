/**
 * The Slider class contains methods to handle the functionality of the slider
 */
export default class Slider {

    private sliderImages: JQuery;

    /**
     * Slider constructor
     *
     * @param delay the delay of a slide
     * @param duration the duration of a slide
     */
    public constructor(private delay: number, private duration: number) {
        this.sliderImages = jQuery('.slider__image');
        this.showSlider();
    }

    /**
     * Get a random number
     *
     * @returns {number} a random number
     */
    private getRandomNumber(): number {
        return Math.floor(Math.random() * this.sliderImages.length);
    }

    /**
     * Show the slider
     */
    private showSlider(): void {
        let randomNumber: number = this.getRandomNumber();
        this.sliderImages.eq(randomNumber).show();
        setInterval((): void => {
            this.sliderImages.eq(randomNumber).fadeOut(this.duration, (): void => {
                randomNumber = this.getRandomNumber();
                this.sliderImages.eq(randomNumber).fadeIn(this.duration);
            });
        }, this.delay);
    }
}