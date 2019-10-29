import { HttpMethod } from './HttpMethod';
import { HttpStatusCode } from './HttpStatusCode';
import { SearchApp } from './SearchApp';
import { Slider } from './Slider';
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
            this.body = document.querySelector('body');
            this.setupMobileNavigation();
            this.setupDownloadLinks();
            let slider = document.getElementById('slider');
            new Slider(slider.dataset.delay, slider.dataset.duration);
            lightbox.option({
                'albumLabel': this.body.dataset.imageText + ' %1 ' + this.body.dataset.ofText + ' %2'
            });
            new SearchApp();
        });
    }
    /**
     * Setup the mobile navigation
     */
    setupMobileNavigation() {
        let mobileMenuTrigger = document.getElementById('mobile-menu-trigger');
        let mobileMenu = document.getElementById('mobile-menu');
        let showMobileMenuClass = 'show-mobile-menu';
        mobileMenuTrigger.addEventListener('click', (event) => {
            event.preventDefault();
            mobileMenuTrigger.classList.toggle('header__nav-trigger--active');
            mobileMenu.classList.toggle('mobile-nav--active');
            document.getElementsByTagName('html')[0].classList.toggle(showMobileMenuClass);
            this.body.classList.toggle(showMobileMenuClass);
        });
        let mobileNavigationArrows = document.querySelectorAll('.mobile-nav__arrow');
        mobileNavigationArrows.forEach((arrow) => {
            arrow.addEventListener('click', (event) => {
                event.preventDefault();
                arrow.classList.toggle('mobile-nav__arrow--rotated');
                let subMenu = arrow.parentNode.parentNode.getElementsByClassName('sub-menu')[0];
                subMenu.style.display = (subMenu.style.display === 'block') ? 'none' : 'block';
            });
        });
    }
    /**
     * Setup the download links
     */
    setupDownloadLinks() {
        let downloadLinks = document.querySelectorAll('.fdwc__link');
        downloadLinks.forEach((downloadLink) => {
            downloadLink.addEventListener('click', () => {
                let xmlHttpRequest = new XMLHttpRequest();
                let fileId = downloadLink.dataset.fileId;
                xmlHttpRequest.open(HttpMethod.Put, '/wp-json/kcapi/v1/fileDownloads?fileid=' + fileId, true);
                xmlHttpRequest.addEventListener('load', () => {
                    if (xmlHttpRequest.status === HttpStatusCode.Ok) {
                        let downloads = downloadLink.parentNode.querySelector('span.fdwc__downloads');
                        downloads.innerText = parseInt(downloads.innerText) + 1;
                    }
                });
                xmlHttpRequest.send();
            });
        });
    }
}
new App();
