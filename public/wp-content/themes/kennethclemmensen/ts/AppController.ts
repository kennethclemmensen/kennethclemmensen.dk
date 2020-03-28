import { EventType } from './enums/EventType';
import { HttpMethod } from './enums/HttpMethod';
import { HttpStatusCode } from './enums/HttpStatusCode';
import { Url } from './enums/Url';
import { IController } from './interfaces/IController';
import { SearchApp } from './SearchApp';
import { ShortcutController } from './ShortcutController';
import { Slider } from './Slider';

/**
 * The AppController class contains methods to handle the functionality of the app
 */
class AppController implements IController {

    private body: HTMLElement;

    /**
     * Initialize the AppController
     */
    public initialize(): void {
        document.addEventListener(EventType.DOMContentLoaded, (): void => {
            this.body = document.body;
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
    private setupSlider() : void {
        let slider: HTMLElement | null = document.getElementById('slider');
        if(slider) {
            let dataset: DOMStringMap = slider.dataset;
            let defaultDelay: number = 500;
            let delay: number = (dataset.delay) ? parseInt(dataset.delay) : defaultDelay;
            let defaultDuration: number = 8000;
            let duration: number = (dataset.duration) ? parseInt(dataset.duration) : defaultDuration;
            new Slider().showSlides(delay, duration);
        }
    }

    /**
     * Setup the event listeners for the mobile menu
     */
    private setupMobileMenu(): void {
        let mobileMenuTrigger: HTMLElement | null = document.getElementById('mobile-menu-trigger');
        let mobileMenu: HTMLElement | null = document.getElementById('mobile-menu');
        let showMobileMenuClass: string = 'show-mobile-menu';
        if(mobileMenuTrigger) {
            mobileMenuTrigger.addEventListener(EventType.Click, (event: Event): void => {
                event.preventDefault();
                if(mobileMenuTrigger) mobileMenuTrigger.classList.toggle('header__nav-trigger--active');
                if(mobileMenu) mobileMenu.classList.toggle('mobile-menu--active');
                document.documentElement.classList.toggle(showMobileMenuClass);
                this.body.classList.toggle(showMobileMenuClass);
            });
        }
        let mobileMenuArrows: NodeListOf<HTMLElement> = document.querySelectorAll('.mobile-menu__arrow');
        mobileMenuArrows.forEach((arrow: HTMLElement): void => {
            arrow.addEventListener(EventType.Click, (event: Event): void => {
                event.preventDefault();
                arrow.classList.toggle('mobile-menu__arrow--rotated');
                if(arrow.parentNode && arrow.parentNode.parentElement) {
                    let subMenu: Element = arrow.parentNode.parentElement.getElementsByClassName('sub-menu')[0];
                    subMenu.classList.toggle('show');
                }
            });
        });
    }

    /**
     * Setup the event listeners for the download links
     */
    private setupDownloadLinks(): void {
        let downloadLinks: NodeListOf<HTMLElement> = document.querySelectorAll('.fdwc__link');
        downloadLinks.forEach((downloadLink: HTMLElement): void => {
            downloadLink.addEventListener(EventType.Click, (): void => {
                let url: string = Url.ApiFileDownloads + downloadLink.dataset.fileId;
                let xhr: XMLHttpRequest = new XMLHttpRequest();
                xhr.open(HttpMethod.Put, url, true);
                xhr.addEventListener(EventType.Load, (): void => {
                    if(xhr.status === HttpStatusCode.Ok) {
                        let xmlHttpRequest: XMLHttpRequest = new XMLHttpRequest();
                        xmlHttpRequest.open(HttpMethod.Get, url, true);
                        xmlHttpRequest.addEventListener(EventType.Load, (): void => {
                            if(downloadLink.parentNode) {
                                let downloads: HTMLElement | null = downloadLink.parentNode.querySelector('span.fdwc__downloads');
                                if(downloads) downloads.innerText = (xmlHttpRequest.status === HttpStatusCode.Ok) ? xmlHttpRequest.responseText : (parseInt(downloads.innerText) + 1).toString();
                            }
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