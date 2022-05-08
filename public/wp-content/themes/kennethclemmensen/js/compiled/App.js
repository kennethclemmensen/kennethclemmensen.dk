import { EventType } from './enums/EventType';
import { SliderAnimation } from './enums/SliderAnimation';
import { FilesApp } from './FilesApp';
import { SearchApp } from './SearchApp';
import { ShortcutController } from './ShortcutController';
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
        const { fromEvent } = rxjs;
        fromEvent(document, EventType.DOMContentLoaded).subscribe(() => {
            this.setupSlider();
            this.setupMobileMenu();
            lightbox.option({
                'albumLabel': this.#body.dataset.imageText + ' %1 ' + this.#body.dataset.ofText + ' %2'
            });
            new FilesApp();
            new SearchApp();
            new ShortcutController();
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
        const mobileMenu = document.getElementById('mobile-menu');
        const showMobileMenuClass = 'show-mobile-menu';
        mobileMenuTrigger?.addEventListener(EventType.Click, (event) => {
            event.preventDefault();
            mobileMenuTrigger?.classList.toggle('header__mobile-menu-trigger--active');
            mobileMenu?.classList.toggle('mobile-menu--active');
            document.documentElement.classList.toggle(showMobileMenuClass);
            this.#body.classList.toggle(showMobileMenuClass);
        });
        const mobileMenuArrows = document.querySelectorAll('.mobile-menu__arrow');
        mobileMenuArrows.forEach((arrow) => {
            arrow.addEventListener(EventType.Click, (event) => {
                event.preventDefault();
                arrow.classList.toggle('mobile-menu__arrow--rotated');
                const subMenu = arrow.parentNode?.parentElement?.getElementsByClassName('sub-menu')[0];
                subMenu?.classList.toggle('show');
            });
        });
    }
}
new App();
