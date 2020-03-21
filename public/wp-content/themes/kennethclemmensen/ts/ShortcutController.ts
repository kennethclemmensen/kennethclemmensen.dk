import { EventType } from './enums/EventType';
import { KeyCode } from './enums/KeyCode';
import { Url } from './enums/Url';
import { IController } from './interfaces/IController';

/**
 * The ShortcutController class contains methods to handle shortcuts
 */
export class ShortcutController implements IController {

    /**
     * Initialize the ShortcutController
     */
    public initialize(): void {
        this.setupKeypressEventListener();
        this.setupKeydownEventListener();
    }

    /**
     * Setup the keypress event listener
     */
    private setupKeypressEventListener(): void {
        document.addEventListener(EventType.Keypress, (event: KeyboardEvent): void => {
            if(event.shiftKey) {
                switch(event.keyCode) {
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
    private setupKeydownEventListener(): void {
        document.addEventListener(EventType.Keydown, (event: KeyboardEvent): void => {
            if(event.ctrlKey && event.shiftKey) {
                switch(event.keyCode) {
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
    private redirectToUrl(url: Url): void {
        location.href = url;
    }
}