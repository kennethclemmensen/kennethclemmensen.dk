import { fromEvent, map, tap } from 'rxjs';
import { ajax } from 'rxjs/ajax';
import { EventType } from '../enums/EventType';
import { HttpMethod } from '../enums/HttpMethod';
/**
 * The Shortcuts class contains methods to handle the shortcuts
 */
export class Shortcuts {
    /**
     * Setup the shortcuts
     */
    setupShortcuts() {
        const keydown$ = fromEvent(document, EventType.Keydown);
        const shortcuts$ = ajax({
            url: '/wp-json/kcapi/v1/shortcuts/',
            method: HttpMethod.Get
        }).pipe(map((response) => {
            const shortcuts = response.response;
            keydown$.pipe(tap((event) => {
                const e = event;
                for (const shortcut of shortcuts) {
                    if (e.altKey === shortcut.altKey && e.ctrlKey === shortcut.ctrlKey && e.shiftKey === shortcut.shiftKey && e.key === shortcut.key) {
                        location.href = shortcut.url;
                        break;
                    }
                }
            })).subscribe();
        }));
        shortcuts$.subscribe();
    }
}
