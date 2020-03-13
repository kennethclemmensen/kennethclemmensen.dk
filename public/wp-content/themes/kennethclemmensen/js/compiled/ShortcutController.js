import { EventType } from './enums/EventType';
import { KeyCode } from './enums/KeyCode';
/**
 * The ShortcutController class contains methods to handle shortcuts
 */
export class ShortcutController {
    /**
     * Initialize a new instance of the ShortcutController class
     */
    constructor() {
        this.setupShortcuts();
    }
    /**
     * Setup the shortcuts
     */
    setupShortcuts() {
        document.addEventListener(EventType.Keypress, (e) => {
            if (e.shiftKey) {
                switch (e.keyCode) {
                    case KeyCode.B:
                        this.redirectToUrl('/billeder');
                        break;
                    case KeyCode.F:
                        this.redirectToUrl('/');
                        break;
                    case KeyCode.S:
                        this.redirectToUrl('/soeg');
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
