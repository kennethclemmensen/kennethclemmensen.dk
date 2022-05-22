import { EventType } from './enums/EventType';
import { KeyCode } from './enums/KeyCode';
import { Url } from './enums/Url';
import { fromEvent } from 'rxjs';
/**
 * The ShortcutController class contains methods to handle shortcuts
 */
export class ShortcutController {
    /**
     * Initialize a new instance of the ShortcutController class
     */
    constructor() {
        this.setupKeypressEventListener();
        this.setupKeydownEventListener();
    }
    /**
     * Setup the keypress event listener
     */
    setupKeypressEventListener() {
        fromEvent(document, EventType.Keypress).subscribe((event) => {
            const e = event;
            if (e.shiftKey) {
                switch (e.key) {
                    case KeyCode.B:
                        this.redirectToUrl(Url.ImagesPage);
                        break;
                    case KeyCode.F:
                        this.redirectToUrl(Url.Frontpage);
                        break;
                    case KeyCode.J:
                        this.redirectToUrl(Url.JavaPage);
                        break;
                    case KeyCode.O:
                        this.redirectToUrl(Url.AboutMePage);
                        break;
                    case KeyCode.P:
                        this.redirectToUrl(Url.PhpPage);
                        break;
                    case KeyCode.S:
                        this.redirectToUrl(Url.SearchPage);
                        break;
                }
            }
        });
    }
    /**
     * Setup the keydown event listener
     */
    setupKeydownEventListener() {
        fromEvent(document, EventType.Keydown).subscribe((event) => {
            const e = event;
            if (e.ctrlKey && e.shiftKey) {
                switch (e.key) {
                    case KeyCode.F:
                        this.redirectToUrl(Url.MoviePage);
                        break;
                    case KeyCode.S:
                        this.redirectToUrl(Url.SitemapPage);
                        break;
                }
            }
        });
    }
    /**
     * Redirect to an url
     *
     * @param url the url to redirect to
     */
    redirectToUrl(url) {
        location.href = url;
    }
}
