/**
 * The Slider class contains methods to handle the functionality of the slider
 */
export class Slider {

    private readonly sliderImages: JQuery;
    private currentRandomNumber: number;

    /**
     * Initialize a new instance of the Slider class
     */
    public constructor() {
        this.sliderImages = $('.slider__image');
        this.currentRandomNumber = -1;
    }

    /**
     * Get a random number between 0 and the number of slides minus 1
     * 
     * @returns a random number
     */
    private getRandomNumber(): number {
        let randomNumber: number = Math.floor(Math.random() * this.sliderImages.length);
        if(this.currentRandomNumber === randomNumber) return this.getRandomNumber();
        this.currentRandomNumber = randomNumber;
        return this.currentRandomNumber;
    }

    /**
     * Show the slides
     *
     * @param delay the delay between two slides
     * @param duration the duration of a slide
     */
    public showSlides(delay: number, duration: number): void {
        let randomNumber: number = this.getRandomNumber();
        this.sliderImages.eq(randomNumber).show();
        setInterval((): void => {
            this.sliderImages.eq(randomNumber).fadeOut(delay, (): void => {
                randomNumber = this.getRandomNumber();
                this.sliderImages.eq(randomNumber).fadeIn(delay);
            });
        }, duration);
    }
}