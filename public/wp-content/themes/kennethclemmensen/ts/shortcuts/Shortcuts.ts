import { fromEvent } from 'rxjs';
import { EventType } from '../enums/EventType';
import { HttpMethod } from '../enums/HttpMethod';
import { HttpStatusCode } from '../enums/HttpStatusCode';
import { Shortcut } from './Shortcut';

/**
 * The Shortcuts class contains methods to handle the shortcuts
 */
export class Shortcuts {

	/**
	 * Setup the shortcuts
	 */
	public setupShortcuts(): void {
		const xhr: XMLHttpRequest = new XMLHttpRequest();
		xhr.open(HttpMethod.Get, '/wp-json/kcapi/v1/shortcuts/', true);
		fromEvent(xhr, EventType.Load).subscribe((): void => {
			const shortcuts: Shortcut[] = (xhr.status === HttpStatusCode.Ok) ? JSON.parse(xhr.responseText) : [];
			fromEvent(document, EventType.Keydown).subscribe((event: Event): void => {
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