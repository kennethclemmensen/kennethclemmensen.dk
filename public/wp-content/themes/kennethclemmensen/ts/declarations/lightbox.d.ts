/**
 * Declares the lightbox variable from the lightbox javascript
 */
 declare const lightbox: {
	option: (options: {
		alwaysShowNavOnTouchDevices?: boolean,
		albumLabel?: string,
		disableScrolling?: boolean,
		fadeDuration?: number,
		fitImagesInViewport?: boolean,
		imageFadeDuration?: number,
		maxWidth?: number,
		maxHeight?: number,
		positionFromTop?: number,
		resizeDuration?: number,
		showImageNumberLabel?: boolean,
		wrapAround?: boolean
	}) => void
};