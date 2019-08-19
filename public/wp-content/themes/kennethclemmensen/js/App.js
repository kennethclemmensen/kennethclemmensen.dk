import SearchApp from './SearchApp';
import Slider from './Slider';
/**
 * The App class contains methods to handle the functionality of the app
 */
class App {
    /**
     * App constructor
     */
    constructor() {
        this.setupApp();
    }
    /**
     * Setup the app
     */
    setupApp() {
        jQuery(() => {
            this.setupMobileNavigation();
            let slider = document.getElementById('slider');
            new Slider(slider.dataset.delay, slider.dataset.duration);
            let body = document.querySelector('body');
            lightbox.option({
                'albumLabel': body.dataset.imageText + ' %1 ' + body.dataset.ofText + ' %2'
            });
            new SearchApp();
        });
    }
    /**
     * Setup the mobile navigation
     */
    setupMobileNavigation() {
        let headerTrigger = '.header__nav-trigger';
        jQuery(headerTrigger).on('click', (event) => {
            event.preventDefault();
            jQuery(headerTrigger).toggleClass('header__nav-trigger--active');
            jQuery('.mobile-nav').toggleClass('mobile-nav--active');
            jQuery('html, body').toggleClass('show-mobile-nav');
        });
        let mobileArrow = '.mobile-nav__arrow';
        jQuery(mobileArrow).on('click', (event) => {
            event.preventDefault();
            jQuery(mobileArrow).toggleClass('mobile-nav__arrow--rotated');
            jQuery(mobileArrow).parent().parent().find('.sub-menu').toggle();
        });
    }
}
new App();
