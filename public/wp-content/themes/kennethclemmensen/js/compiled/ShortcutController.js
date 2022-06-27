import { EventType } from './enums/EventType';
import { Url } from './enums/Url';
import { fromEvent } from 'rxjs';
import { HttpMethod } from './enums/HttpMethod';
import { HttpStatusCode } from './enums/HttpStatusCode';
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
        const xhr = new XMLHttpRequest();
        xhr.open(HttpMethod.Get, Url.ApiShortcuts, true);
        fromEvent(xhr, EventType.Load).subscribe(() => {
            const shortcuts = (xhr.status === HttpStatusCode.Ok) ? JSON.parse(xhr.responseText) : [];
            fromEvent(document, EventType.Keydown).subscribe((event) => {
                event.preventDefault();
                const e = event;
                for (const shortcut of shortcuts) {
                    if (e.altKey === shortcut.altKey && e.ctrlKey === shortcut.ctrlKey && e.shiftKey === shortcut.shiftKey && e.key === shortcut.key) {
                        location.href = shortcut.url;
                        break;
                    }
                }
            });
        });
        xhr.send();
    }
}
