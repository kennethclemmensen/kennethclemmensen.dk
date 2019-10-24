/**
 * The Slider class contains methods to handle the functionality of the slider
 */
export class Slider {

    private sliderImages: JQuery;
    private currentRandomNumber: number;

    /**
     * Slider constructor
     *
     * @param delay the delay of a slide
     * @param duration the duration of a slide
     */
    public constructor(private delay: number, private duration: number) {
        this.sliderImages = jQuery('.slider__image');
        this.currentRandomNumber = -1;
        this.showSlider();
    }

    /**
     * Get a random number between 0 and the number of slides minus 1
     *
     * @returns {number} a random number
     */
    private getRandomNumber(): number {
        let randomNumber: number = Math.floor(Math.random() * this.sliderImages.length);
        if(this.currentRandomNumber === randomNumber) return this.getRandomNumber();
        this.currentRandomNumber = randomNumber;
        return this.currentRandomNumber;
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