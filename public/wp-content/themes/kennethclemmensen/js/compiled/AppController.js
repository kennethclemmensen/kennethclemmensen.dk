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
     * Initialize a new instance of the AppController class
     */
    constructor() {
        this.body = document.body;
    }
    /**
     * Initialize the AppController
     */
    initialize() {
        document.addEventListener(EventType.DOMContentLoaded, () => {
            this.setupSlider();
            this.setupMobileMenu();
            this.setupDownloadLinks();
            lightbox.option({
                'albumLabel': this.body.dataset.imageText + ' %1 ' + this.body.dataset.ofText + ' %2'
            });
            new SearchApp();
            new ShortcutController().initialize();
        });
    }
    /**
     * Setup the slider
     */
    setupSlider() {
        let slider = document.getElementById('slider');
        if (slider) {
            let dataset = slider.dataset;
            let defaultDelay = 500;
            let delay = (dataset.delay) ? parseInt(dataset.delay) : defaultDelay;
            let defaultDuration = 8000;
            let duration = (dataset.duration) ? parseInt(dataset.duration) : defaultDuration;
            new Slider().showSlides(delay, duration);
        }
    }
    /**
     * Setup the event listeners for the mobile menu
     */
    setupMobileMenu() {
        let mobileMenuTrigger = document.getElementById('mobile-menu-trigger');
        let mobileMenu = document.getElementById('mobile-menu');
        let showMobileMenuClass = 'show-mobile-menu';
        if (mobileMenuTrigger) {
            mobileMenuTrigger.addEventListener(EventType.Click, (event) => {
                event.preventDefault();
                if (mobileMenuTrigger && mobileMenu) {
                    mobileMenuTrigger.classList.toggle('header__nav-trigger--active');
                    mobileMenu.classList.toggle('mobile-menu--active');
                    document.documentElement.classList.toggle(showMobileMenuClass);
                    this.body.classList.toggle(showMobileMenuClass);
                }
            });
        }
        let mobileMenuArrows = document.querySelectorAll('.mobile-menu__arrow');
        mobileMenuArrows.forEach((arrow) => {
            arrow.addEventListener(EventType.Click, (event) => {
                event.preventDefault();
                if (arrow.parentNode && arrow.parentNode.parentElement) {
                    arrow.classList.toggle('mobile-menu__arrow--rotated');
                    let subMenu = arrow.parentNode.parentElement.getElementsByClassName('sub-menu')[0];
                    subMenu.classList.toggle('show');
                }
            });
        });
    }
    /**
     * Setup the event listeners for the download links
     */
    setupDownloadLinks() {
        let downloadLinks = document.querySelectorAll('.kc-file-download-link');
        downloadLinks.forEach((downloadLink) => {
            downloadLink.addEventListener(EventType.Click, () => {
                let url = Url.ApiFileDownloads + downloadLink.dataset.fileId;
                let xhr = new XMLHttpRequest();
                xhr.open(HttpMethod.Put, url, true);
                xhr.addEventListener(EventType.Load, () => {
                    if (xhr.status === HttpStatusCode.Ok) {
                        if (downloadLink.parentNode) {
                            let downloads = downloadLink.parentNode.querySelector('span.kc-file-downloads');
                            if (downloads)
                                this.updateNumberOfDownloads(downloads, url);
                        }
                    }
                });
                xhr.send();
            });
        });
    }
    /**
     * Update the number of downloads
     *
     * @param downloadsElement the number of downloads element
     * @param url the url to use to get the number of downloads
     */
    updateNumberOfDownloads(downloadsElement, url) {
        let xhr = new XMLHttpRequest();
        xhr.open(HttpMethod.Get, url, true);
        xhr.addEventListener(EventType.Load, () => {
            if (xhr.status === HttpStatusCode.Ok) {
                downloadsElement.innerText = xhr.responseText;
            }
            else {
                downloadsElement.innerText = (parseInt(downloadsElement.innerText) + 1).toString();
            }
        });
        xhr.send();
    }
}
new AppController().initialize();
