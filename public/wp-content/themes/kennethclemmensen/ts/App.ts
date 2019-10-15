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
        let mobileNavigationTrigger: any = document.getElementsByClassName('header__nav-trigger')[0];
        let mobileNavigation: any = document.getElementsByClassName('mobile-nav')[0];
        let showMobileNavigationClass: string = 'show-mobile-nav';
        mobileNavigationTrigger.addEventListener('click', (event: Event): void => {
            event.preventDefault();
            mobileNavigationTrigger.classList.toggle('header__nav-trigger--active');
            mobileNavigation.classList.toggle('mobile-nav--active');
            document.getElementsByTagName('html')[0].classList.toggle(showMobileNavigationClass);
            document.getElementsByTagName('body')[0].classList.toggle(showMobileNavigationClass);
        });
        let mobileNavigationArrows: any = document.querySelectorAll('.mobile-nav__arrow');
        mobileNavigationArrows.forEach((a: any): void => a.addEventListener('click', (event: Event): void => {
            event.preventDefault();
            a.classList.toggle('mobile-nav__arrow--rotated');
            let subMenu: any = a.parentNode.parentNode.getElementsByClassName('sub-menu')[0];
            subMenu.style.display = (subMenu.style.display === 'block') ? 'none' : 'block';
        }));
    }
}

new App();