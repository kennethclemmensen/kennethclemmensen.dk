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
        document.addEventListener('DOMContentLoaded', (): void => {
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
        let mobileNavigationTrigger: any = document.getElementById('mobile-menu-trigger');
        let mobileNavigation: any = document.getElementById('mobile-menu');
        let showMobileMenuClass: string = 'show-mobile-nav';
        mobileNavigationTrigger.addEventListener('click', (event: Event): void => {
            event.preventDefault();
            mobileNavigationTrigger.classList.toggle('header__nav-trigger--active');
            mobileNavigation.classList.toggle('mobile-nav--active');
            document.getElementsByTagName('html')[0].classList.toggle(showMobileMenuClass);
            document.getElementsByTagName('body')[0].classList.toggle(showMobileMenuClass);
        });
        let mobileNavigationArrows: any = document.querySelectorAll('.mobile-nav__arrow');
        mobileNavigationArrows.forEach((arrow: any): void => arrow.addEventListener('click', (event: Event): void => {
            event.preventDefault();
            arrow.classList.toggle('mobile-nav__arrow--rotated');
            let subMenu: any = arrow.parentNode.parentNode.getElementsByClassName('sub-menu')[0];
            subMenu.style.display = (subMenu.style.display === 'block') ? 'none' : 'block';
        }));
    }
}

new App();