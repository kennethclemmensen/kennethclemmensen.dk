import { fromEvent, map, tap } from 'rxjs';
import { AjaxResponse, ajax } from 'rxjs/ajax';
import { EventType } from '../enums/EventType';
import { HttpMethod } from '../enums/HttpMethod';
import { Shortcut } from './Shortcut';

/**
 * The Shortcuts class contains methods to handle the shortcuts
 */
export class Shortcuts {

	/**
	 * Setup the shortcuts
	 */
	public setupShortcuts(): void {
		ajax({
			url: '/wp-json/kcapi/v1/shortcuts/',
			method: HttpMethod.Get,
			headers: {
				'X-WP-Nonce': httpHeaderValue.nonce
			}
		}).pipe(
			map((response: AjaxResponse<unknown>) => {
				const shortcuts: Shortcut[] = response.response as Shortcut[];
				fromEvent(document, EventType.Keydown).pipe(
					tap((event: Event): void => {
						const e = event as KeyboardEvent;
						for(const shortcut of shortcuts) {
							if(e.altKey === shortcut.altKey && e.ctrlKey === shortcut.ctrlKey && e.shiftKey === shortcut.shiftKey && e.key === shortcut.key) {
								location.href = shortcut.url;
								break;
							}
						}
					})
				).subscribe();
			})
		).subscribe();
	}
}