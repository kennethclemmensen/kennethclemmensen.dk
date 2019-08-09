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
        $.when($.ready).then(() => {
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
        $('.header__nav-trigger').on('click', (event) => {
            event.preventDefault();
            $(this).toggleClass('header__nav-trigger--active');
            $('.mobile-nav').toggleClass('mobile-nav--active');
            $('html, body').toggleClass('show-mobile-nav');
        });
        $('.mobile-nav__arrow').on('click', (event) => {
            event.preventDefault();
            $(this).toggleClass('mobile-nav__arrow--rotated');
            $(this).parent().parent().find('.sub-menu').toggle();
        });
    }
}
new App();
