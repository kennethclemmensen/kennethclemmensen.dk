import { EventType } from './enums/EventType';
import { KeyCode } from './enums/KeyCode';
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
    private setupKeydownEventListener(): void {
        document.addEventListener(EventType.Keydown, (event: KeyboardEvent): void => {
            if(event.ctrlKey && event.shiftKey) {
                switch(event.keyCode) {
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
    private redirectToUrl(url: string): void {
        location.href = url;
    }
}