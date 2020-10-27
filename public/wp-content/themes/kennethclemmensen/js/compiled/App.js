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
    /**
     * Initialize a new instance of the App class
     */
    constructor() {
        this.body = document.body;
        document.addEventListener(EventType.DOMContentLoaded, () => {
            this.setupSlider();
            this.setupMobileMenu();
            lightbox.option({
                'albumLabel': this.body.dataset.imageText + ' %1 ' + this.body.dataset.ofText + ' %2'
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
        var _a;
        let slider = document.getElementById('slider');
        let dataset = slider === null || slider === void 0 ? void 0 : slider.dataset;
        let defaultDelay = 500;
        let delay = (dataset === null || dataset === void 0 ? void 0 : dataset.delay) ? parseInt(dataset.delay) : defaultDelay;
        let defaultDuration = 8000;
        let duration = (dataset === null || dataset === void 0 ? void 0 : dataset.duration) ? parseInt(dataset.duration) : defaultDuration;
        let animation = (_a = dataset === null || dataset === void 0 ? void 0 : dataset.animation) !== null && _a !== void 0 ? _a : SliderAnimation.Fade;
        new Slider().showSlides(delay, duration, animation);
    }
    /**
     * Setup the event listeners for the mobile menu
     */
    setupMobileMenu() {
        let mobileMenuTrigger = document.getElementById('mobile-menu-trigger');
        let mobileMenu = document.getElementById('mobile-menu');
        let showMobileMenuClass = 'show-mobile-menu';
        mobileMenuTrigger === null || mobileMenuTrigger === void 0 ? void 0 : mobileMenuTrigger.addEventListener(EventType.Click, (event) => {
            event.preventDefault();
            mobileMenuTrigger === null || mobileMenuTrigger === void 0 ? void 0 : mobileMenuTrigger.classList.toggle('header__nav-trigger--active');
            mobileMenu === null || mobileMenu === void 0 ? void 0 : mobileMenu.classList.toggle('mobile-menu--active');
            document.documentElement.classList.toggle(showMobileMenuClass);
            this.body.classList.toggle(showMobileMenuClass);
        });
        let mobileMenuArrows = document.querySelectorAll('.mobile-menu__arrow');
        mobileMenuArrows.forEach((arrow) => {
            arrow.addEventListener(EventType.Click, (event) => {
                var _a, _b;
                event.preventDefault();
                arrow.classList.toggle('mobile-menu__arrow--rotated');
                let subMenu = (_b = (_a = arrow.parentNode) === null || _a === void 0 ? void 0 : _a.parentElement) === null || _b === void 0 ? void 0 : _b.getElementsByClassName('sub-menu')[0];
                subMenu === null || subMenu === void 0 ? void 0 : subMenu.classList.toggle('show');
            });
        });
    }
}
new App();
