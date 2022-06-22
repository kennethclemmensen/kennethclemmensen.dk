import { KeyCode } from '../enums/KeyCode';
import { Url } from '../enums/Url';

/**
 * The Shortcut type defines the properties for a shortcut
 */
export type Shortcut = {
	altKey: boolean,
	ctrlKey: boolean,
	shiftKey: boolean,
	keyCode: KeyCode,
	url: Url
}