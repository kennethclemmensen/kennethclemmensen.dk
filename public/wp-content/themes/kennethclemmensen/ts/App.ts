import { EventType } from './enums/EventType';
import { SliderAnimation } from './enums/SliderAnimation';
import { FilesApp } from './FilesApp';
import { SearchApp } from './SearchApp';
import { ShortcutController } from './ShortcutController';
import { Slider } from './Slider';
import { fromEvent } from 'rxjs';

/**
 * The App class contains methods to handle the functionality of the app
 */
class App {

	readonly #body: HTMLElement;

	/**
	 * Initialize a new instance of the App class
	 */
	public constructor() {
		this.#body = document.body;
		fromEvent(document, EventType.DOMContentLoaded).subscribe((): void => {
			this.setupSlider();
			this.setupMobileMenu();
			lightbox.option({
				'albumLabel': `${this.#body.dataset.imageText} %1 ${this.#body.dataset.ofText} %2`
			});
			new FilesApp();
			new SearchApp();
			new ShortcutController();
		});
	}

	/**
	 * Setup the slider
	 */
	private setupSlider(): void {
		const slider: HTMLElement | null = document.getElementById('slider');
		const dataset: DOMStringMap | undefined = slider?.dataset;
		const defaultDelay: number = 500;
		const delay: number = (dataset?.delay) ? parseInt(dataset.delay) : defaultDelay;
		const defaultDuration: number = 8000;
		const duration: number = (dataset?.duration) ? parseInt(dataset.duration) : defaultDuration;
		const animation: string = dataset?.animation ?? SliderAnimation.Fade;
		new Slider().showSlides(delay, duration, animation);
	}

	/**
	 * Setup the event listeners for the mobile menu
	 */
	private setupMobileMenu(): void {
		const mobileMenuTrigger: HTMLElement | null = document.getElementById('mobile-menu-trigger');
		if(!mobileMenuTrigger) return;
		const mobileMenu: HTMLElement | null = document.getElementById('mobile-menu');
		const showMobileMenuClass: string = 'show-mobile-menu';
		fromEvent(mobileMenuTrigger, EventType.Click).subscribe((event: Event): void => {
			event.preventDefault();
			mobileMenuTrigger?.classList.toggle('header__mobile-menu-trigger--active');
			mobileMenu?.classList.toggle('mobile-menu--active');
			document.documentElement.classList.toggle(showMobileMenuClass);
			this.#body.classList.toggle(showMobileMenuClass);
		});
		const mobileMenuArrows: NodeListOf<HTMLElement> = document.querySelectorAll('.mobile-menu__arrow');
		mobileMenuArrows.forEach((arrow: HTMLElement): void => {
			fromEvent(arrow, EventType.Click).subscribe((event: Event): void => {
				event.preventDefault();
				arrow.classList.toggle('mobile-menu__arrow--rotated');
				const subMenu: Element | undefined = arrow.parentNode?.parentElement?.getElementsByClassName('sub-menu')[0];
				subMenu?.classList.toggle('show');
			});
		});
	}
}

new App();