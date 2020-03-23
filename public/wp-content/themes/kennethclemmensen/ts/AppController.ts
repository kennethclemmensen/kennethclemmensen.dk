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
            this.setupApp();
        });
    }

    /**
     * Setup the app
     */
    private setupApp(): void {
        let shortcutController: IController = new ShortcutController();
        shortcutController.initialize();
        this.setupMobileMenu();
        this.setupDownloadLinks();
        let slider: any = document.getElementById('slider');
        new Slider().showSlides(slider.dataset.delay, slider.dataset.duration);
        lightbox.option({
            'albumLabel': this.body.dataset.imageText + ' %1 ' + this.body.dataset.ofText + ' %2'
        });
        new SearchApp();
    }

    /**
     * Setup the event listeners for the mobile menu
     */
    private setupMobileMenu(): void {
        let mobileMenuTrigger: any = document.getElementById('mobile-menu-trigger');
        let mobileMenu: any = document.getElementById('mobile-menu');
        let showMobileMenuClass: string = 'show-mobile-menu';
        mobileMenuTrigger.addEventListener(EventType.Click, (event: Event): void => {
            event.preventDefault();
            mobileMenuTrigger.classList.toggle('header__nav-trigger--active');
            mobileMenu.classList.toggle('mobile-menu--active');
            document.documentElement.classList.toggle(showMobileMenuClass);
            this.body.classList.toggle(showMobileMenuClass);
        });
        let mobileMenuArrows: NodeListOf<HTMLElement> = document.querySelectorAll('.mobile-menu__arrow');
        mobileMenuArrows.forEach((arrow: any): void => {
            arrow.addEventListener(EventType.Click, (event: Event): void => {
                event.preventDefault();
                arrow.classList.toggle('mobile-menu__arrow--rotated');
                let subMenu: HTMLElement = arrow.parentNode.parentNode.getElementsByClassName('sub-menu')[0];
                subMenu.classList.toggle('show');
            });
        });
    }

    /**
     * Setup the event listeners for the download links
     */
    private setupDownloadLinks(): void {
        let downloadLinks: NodeListOf<HTMLElement> = document.querySelectorAll('.fdwc__link');
        downloadLinks.forEach((downloadLink: any): void => {
            downloadLink.addEventListener(EventType.Click, (): void => {
                let url: string = Url.ApiFileDownloads + downloadLink.dataset.fileId;
                let xhr: XMLHttpRequest = new XMLHttpRequest();
                xhr.open(HttpMethod.Put, url, true);
                xhr.addEventListener(EventType.Load, (): void => {
                    if(xhr.status === HttpStatusCode.Ok) {
                        let xmlHttpRequest: XMLHttpRequest = new XMLHttpRequest();
                        xmlHttpRequest.open(HttpMethod.Get, url, true);
                        xmlHttpRequest.addEventListener(EventType.Load, (): void => {
                            let downloads: any = downloadLink.parentNode.querySelector('span.fdwc__downloads');
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