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
        jQuery.when(jQuery.ready).then((): void => {
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
        jQuery('.header__nav-trigger').on('click', (event: Event): void => {
            event.preventDefault();
            jQuery(this).toggleClass('header__nav-trigger--active');
            jQuery('.mobile-nav').toggleClass('mobile-nav--active');
            jQuery('html, body').toggleClass('show-mobile-nav');
        });
        jQuery('.mobile-nav__arrow').on('click', (event: Event): void => {
            event.preventDefault();
            jQuery(this).toggleClass('mobile-nav__arrow--rotated');
            jQuery(this).parent().parent().find('.sub-menu').toggle();
        });
    }
}

new App();