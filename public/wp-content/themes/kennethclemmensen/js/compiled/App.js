import { from, fromEvent, tap } from 'rxjs';
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
    constructor() {
        fromEvent(document, EventType.DOMContentLoaded).pipe(tap(() => {
            this.setupMobileMenu();
            new Slider().showSlides();
            new Shortcuts().setupShortcuts();
            new Gallery();
            new FilesApp();
            new SearchApp();
        })).subscribe();
    }
    /**
     * Setup the event listeners for the mobile menu
     */
    setupMobileMenu() {
        const mobileMenuTrigger = document.getElementById('mobile-menu-trigger');
        const mobileMenu = document.getElementById('mobile-menu');
        const showMobileMenuClass = 'show-mobile-menu';
        if (mobileMenuTrigger != null && mobileMenu != null) {
            fromEvent(mobileMenuTrigger, EventType.Click).pipe(tap((event) => {
                event.preventDefault();
                mobileMenuTrigger.classList.toggle('header__mobile-menu-trigger--active');
                mobileMenu.classList.toggle('mobile-menu--active');
                document.documentElement.classList.toggle(showMobileMenuClass);
                document.body.classList.toggle(showMobileMenuClass);
            })).subscribe();
        }
        from(document.querySelectorAll('.mobile-menu__arrow')).forEach((arrow) => {
            fromEvent(arrow, EventType.Click).pipe(tap((event) => {
                event.preventDefault();
                arrow.classList.toggle('mobile-menu__arrow--rotated');
                const subMenu = arrow.parentNode?.parentElement?.getElementsByClassName('sub-menu')[0];
                if (subMenu != null) {
                    subMenu.classList.toggle('show');
                }
            })).subscribe();
        });
    }
}
new App();
