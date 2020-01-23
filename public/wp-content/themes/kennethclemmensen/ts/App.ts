import { EventType } from './enums/EventType';
import { HttpMethod } from './enums/HttpMethod';
import { HttpStatusCode } from './enums/HttpStatusCode';
import { SearchApp } from './SearchApp';
import { Slider } from './Slider';

/**
 * The App class contains methods to handle the functionality of the app
 */
class App {

    private body: HTMLBodyElement | null;

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
        document.addEventListener(EventType.DOMContentLoaded, (): void => {
            this.body = document.querySelector('body');
            this.setupMobileNavigation();
            this.setupDownloadLinks();
            let slider: any = document.getElementById('slider');
            new Slider(slider.dataset.delay, slider.dataset.duration);
            lightbox.option({
                'albumLabel': this.body?.dataset.imageText + ' %1 ' + this.body?.dataset.ofText + ' %2'
            });
            new SearchApp();
        });
    }

    /**
     * Setup the mobile navigation
     */
    private setupMobileNavigation(): void {
        let mobileMenuTrigger: HTMLElement | null = document.getElementById('mobile-menu-trigger');
        let mobileMenu: HTMLElement | null = document.getElementById('mobile-menu');
        let showMobileMenuClass: string = 'show-mobile-menu';
        mobileMenuTrigger?.addEventListener(EventType.Click, (event: Event): void => {
            event.preventDefault();
            mobileMenuTrigger?.classList.toggle('header__nav-trigger--active');
            mobileMenu?.classList.toggle('mobile-nav--active');
            document.getElementsByTagName('html')[0].classList.toggle(showMobileMenuClass);
            this.body?.classList.toggle(showMobileMenuClass);
        });
        let mobileNavigationArrows: any = document.querySelectorAll('.mobile-nav__arrow');
        mobileNavigationArrows.forEach((arrow: any): void => {
            arrow.addEventListener(EventType.Click, (event: Event): void => {
                event.preventDefault();
                arrow.classList.toggle('mobile-nav__arrow--rotated');
                let subMenu: HTMLElement = arrow.parentNode.parentNode.getElementsByClassName('sub-menu')[0];
                subMenu.style.display = (subMenu.style.display === 'block') ? 'none' : 'block';
            });
        });
    }

    /**
     * Setup the download links
     */
    private setupDownloadLinks(): void {
        let downloadLinks: any = document.querySelectorAll('.fdwc__link');
        downloadLinks.forEach((downloadLink: any): void => {
            downloadLink.addEventListener(EventType.Click, (): void => {
                let xmlHttpRequest: XMLHttpRequest = new XMLHttpRequest();
                let fileId: number = downloadLink.dataset.fileId;
                let url: string = '/wp-json/kcapi/v1/fileDownloads?fileid=' + fileId;
                xmlHttpRequest.open(HttpMethod.Put, url, true);
                xmlHttpRequest.addEventListener(EventType.Load, (): void => {
                    if(xmlHttpRequest.status === HttpStatusCode.Ok) {
                        let xhr: XMLHttpRequest = new XMLHttpRequest();
                        xhr.open(HttpMethod.Get, url, true);
                        xhr.addEventListener(EventType.Load, (): void => {
                            let downloads: any = downloadLink.parentNode.querySelector('span.fdwc__downloads');
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