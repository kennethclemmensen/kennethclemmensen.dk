import { fromEvent } from 'rxjs';
import { EventType } from './enums/EventType';
import { FilesApp } from './FilesApp';
import { Gallery } from './gallery/Gallery';
import { SearchApp } from './SearchApp';
import { Slider } from './slider/Slider';
import { Shortcuts } from './shortcuts/Shortcuts';

/**
 * The App class contains methods to handle the functionality of the app
 */
class App {

	/**
	 * Initialize a new instance of the App class
	 */
	public constructor() {
		fromEvent(document, EventType.DOMContentLoaded)
		.subscribe((): void => {
			this.setupMobileMenu();
			new Slider().showSlides();
			new Shortcuts().setupShortcuts();
			new Gallery();
			new FilesApp();
			new SearchApp();
		});
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
			document.body.classList.toggle(showMobileMenuClass);
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