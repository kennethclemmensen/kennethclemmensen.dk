import { EventType } from './enums/EventType';
import { HttpMethod } from './enums/HttpMethod';
import { HttpStatusCode } from './enums/HttpStatusCode';
import { Url } from './enums/Url';
import { SearchApp } from './SearchApp';
import { ShortcutController } from './ShortcutController';
import { Slider } from './Slider';
/**
 * The AppController class contains methods to handle the functionality of the app
 */
class AppController {
    /**
     * Initialize the AppController
     */
    initialize() {
        document.addEventListener(EventType.DOMContentLoaded, () => {
            this.body = document.body;
            this.setupApp();
        });
    }
    /**
     * Setup the app
     */
    setupApp() {
        let shortcutController = new ShortcutController();
        shortcutController.initialize();
        this.setupMobileMenu();
        this.setupDownloadLinks();
        let slider = document.getElementById('slider');
        new Slider().showSlides(slider.dataset.delay, slider.dataset.duration);
        lightbox.option({
            'albumLabel': this.body.dataset.imageText + ' %1 ' + this.body.dataset.ofText + ' %2'
        });
        new SearchApp();
    }
    /**
     * Setup the event listeners for the mobile menu
     */
    setupMobileMenu() {
        let mobileMenuTrigger = document.getElementById('mobile-menu-trigger');
        let mobileMenu = document.getElementById('mobile-menu');
        let showMobileMenuClass = 'show-mobile-menu';
        mobileMenuTrigger.addEventListener(EventType.Click, (event) => {
            event.preventDefault();
            mobileMenuTrigger.classList.toggle('header__nav-trigger--active');
            mobileMenu.classList.toggle('mobile-menu--active');
            document.documentElement.classList.toggle(showMobileMenuClass);
            this.body.classList.toggle(showMobileMenuClass);
        });
        let mobileMenuArrows = document.querySelectorAll('.mobile-menu__arrow');
        mobileMenuArrows.forEach((arrow) => {
            arrow.addEventListener(EventType.Click, (event) => {
                event.preventDefault();
                arrow.classList.toggle('mobile-menu__arrow--rotated');
                let subMenu = arrow.parentNode.parentNode.getElementsByClassName('sub-menu')[0];
                subMenu.classList.toggle('show');
            });
        });
    }
    /**
     * Setup the event listeners for the download links
     */
    setupDownloadLinks() {
        let downloadLinks = document.querySelectorAll('.fdwc__link');
        downloadLinks.forEach((downloadLink) => {
            downloadLink.addEventListener(EventType.Click, () => {
                let url = Url.ApiFileDownloads + downloadLink.dataset.fileId;
                let xhr = new XMLHttpRequest();
                xhr.open(HttpMethod.Put, url, true);
                xhr.addEventListener(EventType.Load, () => {
                    if (xhr.status === HttpStatusCode.Ok) {
                        let xmlHttpRequest = new XMLHttpRequest();
                        xmlHttpRequest.open(HttpMethod.Get, url, true);
                        xmlHttpRequest.addEventListener(EventType.Load, () => {
                            let downloads = downloadLink.parentNode.querySelector('span.fdwc__downloads');
                            downloads.innerText = (xmlHttpRequest.status === HttpStatusCode.Ok) ? xmlHttpRequest.responseText : parseInt(downloads.innerText) + 1;
                        });
                        xmlHttpRequest.send();
                    }
                });
                xhr.send();
            });
        });
    }
}
new AppController().initialize();
