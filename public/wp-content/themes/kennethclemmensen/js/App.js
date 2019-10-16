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
        document.addEventListener('DOMContentLoaded', () => {
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
        let mobileNavigationTrigger = document.getElementById('mobile-menu-trigger');
        let mobileNavigation = document.getElementById('mobile-menu');
        let showMobileMenuClass = 'show-mobile-nav';
        mobileNavigationTrigger.addEventListener('click', (event) => {
            event.preventDefault();
            mobileNavigationTrigger.classList.toggle('header__nav-trigger--active');
            mobileNavigation.classList.toggle('mobile-nav--active');
            document.getElementsByTagName('html')[0].classList.toggle(showMobileMenuClass);
            document.getElementsByTagName('body')[0].classList.toggle(showMobileMenuClass);
        });
        let mobileNavigationArrows = document.querySelectorAll('.mobile-nav__arrow');
        mobileNavigationArrows.forEach((arrow) => arrow.addEventListener('click', (event) => {
            event.preventDefault();
            arrow.classList.toggle('mobile-nav__arrow--rotated');
            let subMenu = arrow.parentNode.parentNode.getElementsByClassName('sub-menu')[0];
            subMenu.style.display = (subMenu.style.display === 'block') ? 'none' : 'block';
        }));
    }
}
new App();
