/**
 * The Slider class contains methods to handle the functionality of the slider
 */
export class Slider {

    private readonly slides: JQuery;
    private currentRandomNumber: number;

    /**
     * Initialize a new instance of the Slider class
     */
    public constructor() {
        this.slides = $('.slider__slide');
        this.currentRandomNumber = -1;
    }

    /**
     * Get a random number between 0 and the number of slides minus 1
     * 
     * @returns a random number
     */
    private getRandomNumber(): number {
        let randomNumber: number = Math.floor(Math.random() * this.slides.length);
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
        let sliderImage: JQuery = $('.slider__image');
        let key: string = 'slide-image';
        this.setBackgroundImage(sliderImage, this.slides.eq(randomNumber).data(key));
        sliderImage.show();
        setInterval((): void => {
            sliderImage.fadeOut(delay, (): void => {
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
    private setBackgroundImage(element: JQuery, backgroundImageUrl: string): void {
        element.css('background-image', 'url('+ backgroundImageUrl + ')');
    }
}