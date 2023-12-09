import { Observable, from, fromEvent, tap } from 'rxjs';
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
		const contentLoaded$: Observable<Event> = fromEvent(document, EventType.DOMContentLoaded);
		contentLoaded$.pipe(
			tap((): void => {
				this.setupMobileMenu();
				new Slider().showSlides();
				new Shortcuts().setupShortcuts();
				new Gallery();
				new FilesApp();
				new SearchApp();
			})
		).subscribe();
	}

	/**
	 * Setup the event listeners for the mobile menu
	 */
	private setupMobileMenu(): void {
		const mobileMenuTrigger: HTMLElement | null = document.getElementById('mobile-menu-trigger');
		const mobileMenu: HTMLElement | null = document.getElementById('mobile-menu');
		const showMobileMenuClass: string = 'show-mobile-menu';
		const mobileMenuArrows$: Observable<Element> = from(document.querySelectorAll('.mobile-menu__arrow'));
		if(mobileMenuTrigger != null && mobileMenu != null) {
			const mobileMenuTriggerClick$: Observable<Event> = fromEvent(mobileMenuTrigger, EventType.Click);
			mobileMenuTriggerClick$.pipe(
				tap((event: Event): void => {
					event.preventDefault();
					mobileMenuTrigger.classList.toggle('header__mobile-menu-trigger--active');
					mobileMenu.classList.toggle('mobile-menu--active');
					document.documentElement.classList.toggle(showMobileMenuClass);
					document.body.classList.toggle(showMobileMenuClass);
				})
			).subscribe();
		}
		mobileMenuArrows$.forEach((arrow: Element): void => {
			const arrowClick$ = fromEvent(arrow, EventType.Click);
			arrowClick$.pipe(
				tap((event: Event): void => {
					event.preventDefault();
					arrow.classList.toggle('mobile-menu__arrow--rotated');
					const subMenu: Element | undefined = arrow.parentNode?.parentElement?.getElementsByClassName('sub-menu')[0];
					if(subMenu != null) {
						subMenu.classList.toggle('show');
					}
				})
			).subscribe();
		});
	}
}

new App();