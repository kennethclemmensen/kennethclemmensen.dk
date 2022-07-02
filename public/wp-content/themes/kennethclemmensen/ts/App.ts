import { fromEvent, scan } from 'rxjs';
import { EventType } from './enums/EventType';
import { HttpMethod } from './enums/HttpMethod';
import { HttpStatusCode } from './enums/HttpStatusCode';
import { SliderAnimation } from './enums/SliderAnimation';
import { FilesApp } from './FilesApp';
import { SearchApp } from './SearchApp';
import { Slider } from './Slider';
import { Shortcut } from './types/Shortcut';

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
		fromEvent(document, EventType.DOMContentLoaded)
		.pipe(scan((): string => `${this.#body.dataset.imageText} %1 ${this.#body.dataset.ofText} %2`, ''))
		.subscribe((albumLabel: string): void => {
			this.setupSlider();
			this.setupMobileMenu();
			this.setupShortcuts();
			lightbox.option({
				albumLabel: albumLabel
			});
			new FilesApp();
			new SearchApp();
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
			mobileMenuTrigger.classList.toggle('header__mobile-menu-trigger--active');
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

	/**
	 * Setup the shortcuts
	 */
	private setupShortcuts(): void {
		const xhr: XMLHttpRequest = new XMLHttpRequest();
		xhr.open(HttpMethod.Get, '/wp-json/kcapi/v1/shortcuts/', true);
		fromEvent(xhr, EventType.Load).subscribe((): void => {
			const shortcuts: Shortcut[] = (xhr.status === HttpStatusCode.Ok) ? JSON.parse(xhr.responseText) : [];
			fromEvent(document, EventType.Keydown).subscribe((event: Event): void => {
				const e = event as KeyboardEvent;
				for(const shortcut of shortcuts) {
					if (e.altKey === shortcut.altKey && e.ctrlKey === shortcut.ctrlKey && e.shiftKey === shortcut.shiftKey && e.key === shortcut.key) {
						location.href = shortcut.url;
						break;
					}
				}
			});
		});
		xhr.send();
	}
}

new App();