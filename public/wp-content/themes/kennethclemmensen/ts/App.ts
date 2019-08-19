import SearchApp from './SearchApp';
import Slider from './Slider';

/**
 * The App class contains methods to handle the functionality of the app
 */
class App {

    /**
     * App constructor
     */
    public constructor() {
        this.setupApp();
    }

    /**
     * Setup the app
     */
    private setupApp(): void {
        jQuery((): void => {
            this.setupMobileNavigation();
            let slider: any = document.getElementById('slider');
            new Slider(slider.dataset.delay, slider.dataset.duration);
            let body: any = document.querySelector('body');
            lightbox.option({
                'albumLabel': body.dataset.imageText + ' %1 ' + body.dataset.ofText + ' %2'
            });
            new SearchApp();
        });
    }

    /**
     * Setup the mobile navigation
     */
    private setupMobileNavigation(): void {
        let headerTrigger = '.header__nav-trigger';
        jQuery(headerTrigger).on('click', (event: Event): void => {
            event.preventDefault();
            jQuery(headerTrigger).toggleClass('header__nav-trigger--active');
            jQuery('.mobile-nav').toggleClass('mobile-nav--active');
            jQuery('html, body').toggleClass('show-mobile-nav');
        });
        let mobileArrow = '.mobile-nav__arrow';
        jQuery(mobileArrow).on('click', (event: Event): void => {
            event.preventDefault();
            jQuery(mobileArrow).toggleClass('mobile-nav__arrow--rotated');
            jQuery(mobileArrow).parent().parent().find('.sub-menu').toggle();
        });
    }
}

new App();