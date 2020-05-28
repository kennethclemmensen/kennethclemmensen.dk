import { SliderAnimation } from './enums/SliderAnimation';

/**
 * The Slider class contains methods to handle the functionality of the slider
 */
export class Slider {

    private readonly slides: HTMLCollectionOf<Element>;
    private currentRandomNumber: number;

    /**
     * Initialize a new instance of the Slider class
     */
    public constructor() {
        this.slides = document.getElementsByClassName('slider__slide');
        this.currentRandomNumber = -1;
    }

    /**
     * Show the slides
     *
     * @param delay the delay between two slides
     * @param duration the duration of a slide
     * @param animation the animation for the slides
     */
    public showSlides(delay: number, duration: number, animation: string): void {
        let sliderImage: HTMLElement | null = document.getElementById('slider-image');
        let randomNumber: number = this.getRandomNumber();
        let name: string = 'data-slide-image';
        let backgroundImageUrl: string | null = this.slides[randomNumber].getAttribute(name);
        if(!sliderImage || !backgroundImageUrl) return;
        this.setBackgroundImage(sliderImage, backgroundImageUrl);
        let startKeyframes: Keyframe[];
        let endKeyframes: Keyframe[];
        let px: string = 'px';
        let width: number = sliderImage.clientWidth;
        let height: number = sliderImage.clientHeight;
        switch(animation) {
            case SliderAnimation.SlideDown:
                startKeyframes = [{ backgroundPositionY: 0 }, { backgroundPositionY: height + px }];
                endKeyframes = [{ backgroundPositionY: height + px }, { backgroundPositionY: 0 }];
                break;
            case SliderAnimation.SlideLeft:
                startKeyframes = [{ backgroundPositionX: 0 }, { backgroundPositionX: -width + px }];
                endKeyframes = [{ backgroundPositionX: -width + px }, { backgroundPositionX: 0 }];
                break;
            case SliderAnimation.SlideRight:
                startKeyframes = [{ backgroundPositionX: 0 }, { backgroundPositionX: width + px }];
                endKeyframes = [{ backgroundPositionX: width + px }, { backgroundPositionX: 0 }];
                break;
            case SliderAnimation.SlideUp:
                startKeyframes = [{ backgroundPositionY: 0 }, { backgroundPositionY: -height + px }];
                endKeyframes = [{ backgroundPositionY: -height + px }, { backgroundPositionY: 0 }];
                break;
            default:
                startKeyframes = [{ opacity: 1 }, { opacity: 0 }];
                endKeyframes = [{ opacity: 0 }, { opacity: 1 }];
                break;
        }
        setInterval((): void => {
            if(sliderImage) {
                sliderImage.animate(startKeyframes, {
                    duration: delay
                }).onfinish = (): void => {
                    randomNumber = this.getRandomNumber();
                    backgroundImageUrl = this.slides[randomNumber].getAttribute(name);
                    if(sliderImage && backgroundImageUrl) {
                        this.setBackgroundImage(sliderImage, backgroundImageUrl);
                        sliderImage.animate(endKeyframes, {
                            duration: delay
                        });
                    }
                };
            }
        }, duration);
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
     * Set a background image on an element
     * 
     * @param element the element to set the background image on
     * @param backgroundImageUrl the background image url
     */
    private setBackgroundImage(element: HTMLElement, backgroundImageUrl: string): void {
        element.style.backgroundImage = 'url("' + backgroundImageUrl + '")';
    }
}