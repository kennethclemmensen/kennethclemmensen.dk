/**
 * Declares the Vue constant from the vue javascript
 */
declare const Vue: {
	createApp(setting: {
		data?: object,
		watch?: object,
		methods?: object,
		components: object
	}): {
		mount: (elementId: string) => void
	};
};