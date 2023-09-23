import { SliderAnimation } from './SliderAnimation';

/**
 * The Slider class contains methods to handle the functionality of the slider
 */
export class Slider {

	readonly #slides: HTMLCollectionOf<Element>;
	readonly #sliderImage: HTMLElement | null;
	#currentRandomNumber: number;
	readonly #delay: number;
	readonly #duration: number;
	readonly #animation: string;

	/**
	 * Initialize a new instance of the Slider class
	 */
	public constructor() {
		this.#slides = document.getElementsByClassName('slider__slide');
		this.#sliderImage = document.getElementById('slider-image');
		this.#currentRandomNumber = -1;
		const slider: HTMLElement | null = document.getElementById('slider');
		const dataset: DOMStringMap | undefined = slider?.dataset;
		const defaultDelay: number = 500;
		this.#delay = (dataset?.delay) ? parseInt(dataset.delay) : defaultDelay;
		const defaultDuration: number = 8000;
		this.#duration = (dataset?.duration) ? parseInt(dataset.duration) : defaultDuration;
		this.#animation = dataset?.animation ?? SliderAnimation.Fade;
	}

	/**
	 * Show the slides
	 */
	public showSlides(): void {
		let randomNumber: number = this.getRandomNumber();
		const name: string = 'data-slide-image';
		let backgroundImageUrl: string | null | undefined = this.#slides[randomNumber]?.getAttribute(name);
		if(!backgroundImageUrl) return;
		this.setBackgroundImage(backgroundImageUrl);
		const startKeyframes: Keyframe[] = this.getStartKeyframes(this.#animation);
		const endKeyframes: Keyframe[] = this.getEndKeyframes(this.#animation);
		setInterval((): void => {
			if(this.#sliderImage) {
				this.#sliderImage.animate(startKeyframes, {
					duration: this.#delay
				}).onfinish = (): void => {
					randomNumber = this.getRandomNumber();
					backgroundImageUrl = this.#slides[randomNumber]?.getAttribute(name);
					if(backgroundImageUrl) this.setBackgroundImage(backgroundImageUrl);
					this.#sliderImage?.animate(endKeyframes, { duration: this.#delay });
				};
			}
		}, this.#duration);
	}

	/**
	 * Get a random number between 0 and the number of slides minus 1
	 * 
	 * @returns a random number
	 */
	private getRandomNumber(): number {
		const randomNumber: number = Math.floor(Math.random() * this.#slides.length);
		if(this.#currentRandomNumber === randomNumber) return this.getRandomNumber();
		this.#currentRandomNumber = randomNumber;
		return this.#currentRandomNumber;
	}

	/**
	 * Set a background image on the slider image
	 * 
	 * @param backgroundImageUrl the background image url
	 */
	private setBackgroundImage(backgroundImageUrl: string): void {
		if(this.#sliderImage) this.#sliderImage.style.backgroundImage = 'url("' + backgroundImageUrl + '")';
	}

	/**
	 * Get the start keyframes based on the animation
	 * 
	 * @param animation the animation
	 * @return the start keyframes
	 */
	private getStartKeyframes(animation: string) : Keyframe[] {
		let startKeyframes: Keyframe[] = [];
		if(this.#sliderImage) {
			const width: number = this.#sliderImage.clientWidth;
			const height: number = this.#sliderImage.clientHeight;
			const px: string = 'px';
			switch(animation) {
				case SliderAnimation.SlideDown:
					startKeyframes = [{ backgroundPositionY: 0 }, { backgroundPositionY: height + px }];
					break;
				case SliderAnimation.SlideLeft:
					startKeyframes = [{ backgroundPositionX: 0 }, { backgroundPositionX: -width + px }];
					break;
				case SliderAnimation.SlideRight:
					startKeyframes = [{ backgroundPositionX: 0 }, { backgroundPositionX: width + px }];
					break;
				case SliderAnimation.SlideUp:
					startKeyframes = [{ backgroundPositionY: 0 }, { backgroundPositionY: -height + px }];
					break;
				default:
					startKeyframes = [{ opacity: 1 }, { opacity: 0 }];
					break;
			}
		}
		return startKeyframes;
	}

	/**
	 * Get the end keyframes based on the animation
	 * 
	 * @param animation the animation
	 * @return the end keyframes
	 */
	private getEndKeyframes(animation: string) : Keyframe[] {
		let endKeyframes: Keyframe[] = [];
		if(this.#sliderImage) {
			const width: number = this.#sliderImage.clientWidth;
			const height: number = this.#sliderImage.clientHeight;
			const px: string = 'px';
			switch(animation) {
				case SliderAnimation.SlideDown:
					endKeyframes = [{ backgroundPositionY: height + px }, { backgroundPositionY: 0 }];
					break;
				case SliderAnimation.SlideLeft:
					endKeyframes = [{ backgroundPositionX: -width + px }, { backgroundPositionX: 0 }];
					break;
				case SliderAnimation.SlideRight:
					endKeyframes = [{ backgroundPositionX: width + px }, { backgroundPositionX: 0 }];
					break;
				case SliderAnimation.SlideUp:
					endKeyframes = [{ backgroundPositionY: -height + px }, { backgroundPositionY: 0 }];
					break;
				default:
					endKeyframes = [{ opacity: 0 }, { opacity: 1 }];
					break;
			}
		}
		return endKeyframes;
	}
}