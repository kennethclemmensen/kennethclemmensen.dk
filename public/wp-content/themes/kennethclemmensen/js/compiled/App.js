var __classPrivateFieldSet = (this && this.__classPrivateFieldSet) || function (receiver, privateMap, value) {
    if (!privateMap.has(receiver)) {
        throw new TypeError("attempted to set private field on non-instance");
    }
    privateMap.set(receiver, value);
    return value;
};
var __classPrivateFieldGet = (this && this.__classPrivateFieldGet) || function (receiver, privateMap) {
    if (!privateMap.has(receiver)) {
        throw new TypeError("attempted to get private field on non-instance");
    }
    return privateMap.get(receiver);
};
var _body;
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
        _body.set(this, void 0);
        __classPrivateFieldSet(this, _body, document.body);
        document.addEventListener(EventType.DOMContentLoaded, () => {
            this.setupSlider();
            this.setupMobileMenu();
            lightbox.option({
                'albumLabel': __classPrivateFieldGet(this, _body).dataset.imageText + ' %1 ' + __classPrivateFieldGet(this, _body).dataset.ofText + ' %2'
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
        const slider = document.getElementById('slider');
        const dataset = slider === null || slider === void 0 ? void 0 : slider.dataset;
        const defaultDelay = 500;
        const delay = (dataset === null || dataset === void 0 ? void 0 : dataset.delay) ? parseInt(dataset.delay) : defaultDelay;
        const defaultDuration = 8000;
        const duration = (dataset === null || dataset === void 0 ? void 0 : dataset.duration) ? parseInt(dataset.duration) : defaultDuration;
        const animation = (_a = dataset === null || dataset === void 0 ? void 0 : dataset.animation) !== null && _a !== void 0 ? _a : SliderAnimation.Fade;
        new Slider().showSlides(delay, duration, animation);
    }
    /**
     * Setup the event listeners for the mobile menu
     */
    setupMobileMenu() {
        const mobileMenuTrigger = document.getElementById('mobile-menu-trigger');
        const mobileMenu = document.getElementById('mobile-menu');
        const showMobileMenuClass = 'show-mobile-menu';
        mobileMenuTrigger === null || mobileMenuTrigger === void 0 ? void 0 : mobileMenuTrigger.addEventListener(EventType.Click, (event) => {
            event.preventDefault();
            mobileMenuTrigger === null || mobileMenuTrigger === void 0 ? void 0 : mobileMenuTrigger.classList.toggle('header__nav-trigger--active');
            mobileMenu === null || mobileMenu === void 0 ? void 0 : mobileMenu.classList.toggle('mobile-menu--active');
            document.documentElement.classList.toggle(showMobileMenuClass);
            __classPrivateFieldGet(this, _body).classList.toggle(showMobileMenuClass);
        });
        const mobileMenuArrows = document.querySelectorAll('.mobile-menu__arrow');
        mobileMenuArrows.forEach((arrow) => {
            arrow.addEventListener(EventType.Click, (event) => {
                var _a, _b;
                event.preventDefault();
                arrow.classList.toggle('mobile-menu__arrow--rotated');
                const subMenu = (_b = (_a = arrow.parentNode) === null || _a === void 0 ? void 0 : _a.parentElement) === null || _b === void 0 ? void 0 : _b.getElementsByClassName('sub-menu')[0];
                subMenu === null || subMenu === void 0 ? void 0 : subMenu.classList.toggle('show');
            });
        });
    }
}
_body = new WeakMap();
new App();
