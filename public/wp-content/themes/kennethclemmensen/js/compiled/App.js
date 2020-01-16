import { EventType } from './enums/EventType';
import { HttpMethod } from './enums/HttpMethod';
import { HttpStatusCode } from './enums/HttpStatusCode';
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
        document.addEventListener(EventType.DOMContentLoaded, () => {
            var _a, _b;
            this.body = document.querySelector('body');
            this.setupMobileNavigation();
            this.setupDownloadLinks();
            let slider = document.getElementById('slider');
            new Slider(slider.dataset.delay, slider.dataset.duration);
            lightbox.option({
                'albumLabel': ((_a = this.body) === null || _a === void 0 ? void 0 : _a.dataset.imageText) + ' %1 ' + ((_b = this.body) === null || _b === void 0 ? void 0 : _b.dataset.ofText) + ' %2'
            });
            new SearchApp();
        });
    }
    /**
     * Setup the mobile navigation
     */
    setupMobileNavigation() {
        var _a;
        let mobileMenuTrigger = document.getElementById('mobile-menu-trigger');
        let mobileMenu = document.getElementById('mobile-menu');
        let showMobileMenuClass = 'show-mobile-menu';
        (_a = mobileMenuTrigger) === null || _a === void 0 ? void 0 : _a.addEventListener(EventType.Click, (event) => {
            var _a, _b, _c;
            event.preventDefault();
            (_a = mobileMenuTrigger) === null || _a === void 0 ? void 0 : _a.classList.toggle('header__nav-trigger--active');
            (_b = mobileMenu) === null || _b === void 0 ? void 0 : _b.classList.toggle('mobile-nav--active');
            document.getElementsByTagName('html')[0].classList.toggle(showMobileMenuClass);
            (_c = this.body) === null || _c === void 0 ? void 0 : _c.classList.toggle(showMobileMenuClass);
        });
        let mobileNavigationArrows = document.querySelectorAll('.mobile-nav__arrow');
        mobileNavigationArrows.forEach((arrow) => {
            arrow.addEventListener(EventType.Click, (event) => {
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
            downloadLink.addEventListener(EventType.Click, () => {
                let xmlHttpRequest = new XMLHttpRequest();
                let fileId = downloadLink.dataset.fileId;
                let url = '/wp-json/kcapi/v1/fileDownloads?fileid=' + fileId;
                xmlHttpRequest.open(HttpMethod.Put, url, true);
                xmlHttpRequest.addEventListener(EventType.Load, () => {
                    if (xmlHttpRequest.status === HttpStatusCode.Ok) {
                        let xhr = new XMLHttpRequest();
                        xhr.open(HttpMethod.Get, url, true);
                        xhr.addEventListener(EventType.Load, () => {
                            let downloads = downloadLink.parentNode.querySelector('span.fdwc__downloads');
                            downloads.innerText = (xhr.status === HttpStatusCode.Ok) ? xhr.responseText : parseInt(downloads.innerText) + 1;
                        });
                        xhr.send();
                    }
                });
                xmlHttpRequest.send();
            });
        });
    }
}
new App();
