import { fromEvent, scan } from 'rxjs';
import { EventType } from './enums/EventType';
import { HttpMethod } from './enums/HttpMethod';
import { HttpStatusCode } from './enums/HttpStatusCode';
import { SliderAnimation } from './enums/SliderAnimation';
import { FilesApp } from './FilesApp';
import { SearchApp } from './SearchApp';
import { Slider } from './Slider';
/**
 * The App class contains methods to handle the functionality of the app
 */
class App {
    #body;
    /**
     * Initialize a new instance of the App class
     */
    constructor() {
        this.#body = document.body;
        fromEvent(document, EventType.DOMContentLoaded)
            .pipe(scan(() => `${this.#body.dataset.imageText} %1 ${this.#body.dataset.ofText} %2`, ''))
            .subscribe((albumLabel) => {
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
    setupSlider() {
        const slider = document.getElementById('slider');
        const dataset = slider?.dataset;
        const defaultDelay = 500;
        const delay = (dataset?.delay) ? parseInt(dataset.delay) : defaultDelay;
        const defaultDuration = 8000;
        const duration = (dataset?.duration) ? parseInt(dataset.duration) : defaultDuration;
        const animation = dataset?.animation ?? SliderAnimation.Fade;
        new Slider().showSlides(delay, duration, animation);
    }
    /**
     * Setup the event listeners for the mobile menu
     */
    setupMobileMenu() {
        const mobileMenuTrigger = document.getElementById('mobile-menu-trigger');
        if (!mobileMenuTrigger)
            return;
        const mobileMenu = document.getElementById('mobile-menu');
        const showMobileMenuClass = 'show-mobile-menu';
        fromEvent(mobileMenuTrigger, EventType.Click).subscribe((event) => {
            event.preventDefault();
            mobileMenuTrigger.classList.toggle('header__mobile-menu-trigger--active');
            mobileMenu?.classList.toggle('mobile-menu--active');
            document.documentElement.classList.toggle(showMobileMenuClass);
            this.#body.classList.toggle(showMobileMenuClass);
        });
        const mobileMenuArrows = document.querySelectorAll('.mobile-menu__arrow');
        mobileMenuArrows.forEach((arrow) => {
            fromEvent(arrow, EventType.Click).subscribe((event) => {
                event.preventDefault();
                arrow.classList.toggle('mobile-menu__arrow--rotated');
                const subMenu = arrow.parentNode?.parentElement?.getElementsByClassName('sub-menu')[0];
                subMenu?.classList.toggle('show');
            });
        });
    }
    /**
     * Setup the shortcuts
     */
    setupShortcuts() {
        const xhr = new XMLHttpRequest();
        xhr.open(HttpMethod.Get, '/wp-json/kcapi/v1/shortcuts/', true);
        fromEvent(xhr, EventType.Load).subscribe(() => {
            const shortcuts = (xhr.status === HttpStatusCode.Ok) ? JSON.parse(xhr.responseText) : [];
            fromEvent(document, EventType.Keydown).subscribe((event) => {
                const e = event;
                for (const shortcut of shortcuts) {
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
