import { EventType } from './enums/EventType';
import { Url } from './enums/Url';
import { fromEvent } from 'rxjs';
import { Shortcut } from './types/Shortcut';
import { HttpMethod } from './enums/HttpMethod';
import { HttpStatusCode } from './enums/HttpStatusCode';

/**
 * The ShortcutController class contains methods to handle shortcuts
 */
export class ShortcutController {

	/**
	 * Initialize a new instance of the ShortcutController class
	 */
	public constructor() {
		this.setupShortcuts();
	}

	/**
	 * Setup the shortcuts
	 */
	private setupShortcuts(): void {
		const xhr: XMLHttpRequest = new XMLHttpRequest();
		xhr.open(HttpMethod.Get, Url.ApiShortcuts, true);
		fromEvent(xhr, EventType.Load).subscribe((): void => {
			const shortcuts: Shortcut[] = (xhr.status === HttpStatusCode.Ok) ? JSON.parse(xhr.responseText) : [];
			fromEvent(document, EventType.Keydown).subscribe((event: Event): void => {
				event.preventDefault();
				const e = event as KeyboardEvent;
				for(const shortcut of shortcuts) {
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