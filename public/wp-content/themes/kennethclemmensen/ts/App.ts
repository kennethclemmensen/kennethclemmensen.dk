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

    private readonly body: HTMLElement;

    /**
     * Initialize a new instance of the App class
     */
    public constructor() {
        this.body = document.body;
        document.addEventListener(EventType.DOMContentLoaded, (): void => {
            this.setupSlider();
            this.setupMobileMenu();
            lightbox.option({
                'albumLabel': this.body.dataset.imageText + ' %1 ' + this.body.dataset.ofText + ' %2'
            });
            new FilesApp();
            new SearchApp();
            new ShortcutController().initialize();
        });
    }

    /**
     * Setup the slider
     */
    private setupSlider(): void {
        let slider: HTMLElement | null = document.getElementById('slider');
        if(slider) {
            let dataset: DOMStringMap = slider.dataset;
            let defaultDelay: number = 500;
            let delay: number = (dataset.delay) ? parseInt(dataset.delay) : defaultDelay;
            let defaultDuration: number = 8000;
            let duration: number = (dataset.duration) ? parseInt(dataset.duration) : defaultDuration;
            let animation: string = (dataset.animation) ? dataset.animation : SliderAnimation.Fade;
            new Slider().showSlides(delay, duration, animation);
        }
    }

    /**
     * Setup the event listeners for the mobile menu
     */
    private setupMobileMenu(): void {
        let mobileMenuTrigger: HTMLElement | null = document.getElementById('mobile-menu-trigger');
        let mobileMenu: HTMLElement | null = document.getElementById('mobile-menu');
        let showMobileMenuClass: string = 'show-mobile-menu';
        if(mobileMenuTrigger) {
            mobileMenuTrigger.addEventListener(EventType.Click, (event: Event): void => {
                event.preventDefault();
                if(mobileMenuTrigger && mobileMenu) {
                    mobileMenuTrigger.classList.toggle('header__nav-trigger--active');
                    mobileMenu.classList.toggle('mobile-menu--active');
                    document.documentElement.classList.toggle(showMobileMenuClass);
                    this.body.classList.toggle(showMobileMenuClass);
                }
            });
        }
        let mobileMenuArrows: NodeListOf<HTMLElement> = document.querySelectorAll('.mobile-menu__arrow');
        mobileMenuArrows.forEach((arrow: HTMLElement): void => {
            arrow.addEventListener(EventType.Click, (event: Event): void => {
                event.preventDefault();
                if(arrow.parentNode && arrow.parentNode.parentElement) {
                    arrow.classList.toggle('mobile-menu__arrow--rotated');
                    let subMenu: Element = arrow.parentNode.parentElement.getElementsByClassName('sub-menu')[0];
                    subMenu.classList.toggle('show');
                }
            });
        });
    }
}

new App();