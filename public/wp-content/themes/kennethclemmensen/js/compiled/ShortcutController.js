import { EventType } from './enums/EventType';
import { KeyCode } from './enums/KeyCode';
/**
 * The ShortcutController class contains methods to handle shortcuts
 */
export class ShortcutController {
    /**
     * Initialize the ShortcutController
     */
    initialize() {
        this.setupKeypressEventListener();
        this.setupKeydownEventListener();
    }
    /**
     * Setup the keypress event listener
     */
    setupKeypressEventListener() {
        document.addEventListener(EventType.Keypress, (event) => {
            if (event.shiftKey) {
                switch (event.keyCode) {
                    case KeyCode.B:
                        this.redirectToUrl('/billeder');
                        break;
                    case KeyCode.F:
                        this.redirectToUrl('/');
                        break;
                    case KeyCode.J:
                        this.redirectToUrl('/java');
                        break;
                    case KeyCode.O:
                        this.redirectToUrl('/om-mig');
                        break;
                    case KeyCode.P:
                        this.redirectToUrl('/php');
                        break;
                    case KeyCode.S:
                        this.redirectToUrl('/soeg');
                        break;
                }
            }
        });
    }
    /**
     * Setup the keydown event listener
     */
    setupKeydownEventListener() {
        document.addEventListener(EventType.Keydown, (event) => {
            if (event.ctrlKey && event.shiftKey) {
                switch (event.keyCode) {
                    case KeyCode.F:
                        this.redirectToUrl('/film');
                        break;
                    case KeyCode.S:
                        this.redirectToUrl('/sitemap');
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
