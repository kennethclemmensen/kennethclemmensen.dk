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
        $.when($.ready).then((): void => {
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
        $('.header__nav-trigger').on('click', (event: Event): void => {
            event.preventDefault();
            $(this).toggleClass('header__nav-trigger--active');
            $('.mobile-nav').toggleClass('mobile-nav--active');
            $('html, body').toggleClass('show-mobile-nav');
        });
        $('.mobile-nav__arrow').on('click', (event: Event): void => {
            event.preventDefault();
            $(this).toggleClass('mobile-nav__arrow--rotated');
            $(this).parent().parent().find('.sub-menu').toggle();
        });
    }
}

new App();