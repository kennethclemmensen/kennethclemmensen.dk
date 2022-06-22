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
        this.setupShortcuts();
    }
    /**
     * Setup the shortcuts
     */
    setupShortcuts() {
        const shortcuts = [
            { altKey: false, ctrlKey: true, shiftKey: true, keyCode: KeyCode.B, url: Url.ImagesPage },
            { altKey: false, ctrlKey: true, shiftKey: true, keyCode: KeyCode.F, url: Url.Frontpage },
            { altKey: false, ctrlKey: true, shiftKey: true, keyCode: KeyCode.J, url: Url.JavaPage },
            { altKey: false, ctrlKey: true, shiftKey: true, keyCode: KeyCode.O, url: Url.AboutMePage },
            { altKey: false, ctrlKey: true, shiftKey: true, keyCode: KeyCode.P, url: Url.PhpPage },
            { altKey: false, ctrlKey: true, shiftKey: true, keyCode: KeyCode.S, url: Url.SearchPage },
            { altKey: true, ctrlKey: true, shiftKey: true, keyCode: KeyCode.F, url: Url.MoviePage },
            { altKey: true, ctrlKey: true, shiftKey: true, keyCode: KeyCode.S, url: Url.SitemapPage }
        ];
        fromEvent(document, EventType.Keydown).subscribe((event) => {
            const e = event;
            for (const shortcut of shortcuts) {
                if (e.altKey === shortcut.altKey && e.ctrlKey === shortcut.ctrlKey && e.shiftKey === shortcut.shiftKey && e.key === shortcut.keyCode) {
                    location.href = shortcut.url;
                }
            }
        });
    }
}
