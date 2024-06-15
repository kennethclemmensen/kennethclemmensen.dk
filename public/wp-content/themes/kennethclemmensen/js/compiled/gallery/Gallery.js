import { fromEvent, tap } from 'rxjs';
import { EventType } from '../enums/EventType';
/**
 * The Gallery class contains methods to handle the functionality of the gallery
 */
export class Gallery {
    #settings;
    #images;
    #numberOfImages;
    #currentImageIndex;
    #linkHiddenClass;
    #overlayVisibleClass;
    #galleryVisibleClass;
    #previousLink;
    #nextLink;
    #gallery;
    #galleryOverlay;
    #imageElement;
    #originalWidth;
    #originalHeight;
    #pixel;
    /**
     * Initialize a new instance of the Gallery class with the gallery settings
     *
     * @param settings the gallery settings
     */
    constructor(settings) {
        document.body.insertAdjacentHTML('beforeend', `
			<div class="gallery-overlay" id="gallery-overlay"></div>
			<div class="gallery" id="gallery">
				<div class="gallery__image-container">
					<div class="gallery__navigation">
						<a class="gallery__navigation-link gallery__navigation-link--previous" id="previous-link"></a>
						<a class="gallery__navigation-link gallery__navigation-link--next" id="next-link"></a>
					</div>
					<img class="gallery__image" id="image">
				</div>
				<div class="gallery__image-info-container">
					<div class="gallery__text-container">
						<span class="gallery__info-text" id="image-title"></span>
						<span class="gallery__info-text gallery__info-text--small" id="image-info"></span>
					</div>
					<a class="gallery__close" id="gallery-close"></a>
				</div>
			</div>
		`);
        this.#settings = settings;
        this.#images = [];
        const images = document.querySelectorAll('.page__gallery-thumbnail-link');
        for (const image of images) {
            this.#images.push(image);
        }
        this.#numberOfImages = this.#images.length;
        this.#currentImageIndex = 0;
        this.#linkHiddenClass = 'gallery__navigation-link--hidden';
        this.#overlayVisibleClass = 'gallery-overlay--visible';
        this.#galleryVisibleClass = 'gallery--visible';
        this.#previousLink = document.getElementById('previous-link');
        this.#nextLink = document.getElementById('next-link');
        this.#gallery = document.getElementById('gallery');
        this.#galleryOverlay = document.getElementById('gallery-overlay');
        this.#imageElement = document.getElementById('image');
        this.#pixel = 'px';
        this.setupEventHandlers();
    }
    /**
     * Setup the event handlers
     */
    setupEventHandlers() {
        const galleryClose = document.getElementById('gallery-close');
        for (const image of this.#images) {
            fromEvent(image, EventType.Click).pipe(tap((event) => {
                event.preventDefault();
                this.#currentImageIndex = this.#images.indexOf(image);
                if (this.#currentImageIndex === 0) {
                    this.hidePreviousLink();
                }
                else if ((this.#currentImageIndex + 1) === this.#numberOfImages) {
                    this.hideNextLink();
                }
                this.showImage();
                this.updateTitle();
                this.showOverlay();
                this.updateImageInfo();
            })).subscribe();
        }
        if (galleryClose != null && this.#galleryOverlay != null && this.#previousLink != null && this.#nextLink != null) {
            fromEvent([galleryClose, this.#galleryOverlay], EventType.Click).pipe(tap((event) => {
                event.preventDefault();
                this.hideOverlay();
            })).subscribe();
            fromEvent([this.#previousLink, this.#nextLink], EventType.Click).pipe(tap((event) => {
                event.preventDefault();
                if (event.target === this.#previousLink) {
                    this.#currentImageIndex--;
                    this.showNextLink();
                    if (this.#currentImageIndex === 0) {
                        this.hidePreviousLink();
                    }
                }
                else {
                    this.#currentImageIndex++;
                    this.showPreviousLink();
                    if ((this.#currentImageIndex + 1) === this.#numberOfImages) {
                        this.hideNextLink();
                    }
                }
                this.showImage();
                this.updateTitle();
                this.updateImageInfo();
            })).subscribe();
        }
        fromEvent(window, EventType.Resize).pipe(tap(() => {
            if (this.#originalWidth != null && this.#originalHeight != null) {
                if (window.innerWidth < this.#originalWidth) {
                    const aspectRatio = this.getAspectRatio(this.#originalWidth, this.#originalHeight);
                    let imageWidth = window.innerWidth;
                    const imageHeight = this.getHeight(imageWidth, aspectRatio);
                    imageWidth = this.getWidth(imageHeight, aspectRatio);
                    if (this.#imageElement != null) {
                        this.#imageElement.style.width = imageWidth + this.#pixel;
                        this.#imageElement.style.height = imageHeight + this.#pixel;
                    }
                }
            }
        })).subscribe();
    }
    /**
     * Get the aspect ration based on the width and height
     *
     * @param width the width
     * @param height the height
     * @returns the aspect ration
     */
    getAspectRatio(width, height) {
        return width / height;
    }
    /**
     * Get the width based on the height and the aspect ratio
     *
     * @param height the height
     * @param aspectRatio the aspect ratio
     * @returns the width
     */
    getWidth(height, aspectRatio) {
        return height * aspectRatio;
    }
    /**
     * Get the height based on the width and the aspect ratio
     *
     * @param width the width
     * @param aspectRatio the aspect ratio
     * @returns the height
     */
    getHeight(width, aspectRatio) {
        return width / aspectRatio;
    }
    /**
     * Show the image
     */
    showImage() {
        const image = this.#images[this.#currentImageIndex];
        if (image != null && this.#imageElement != null) {
            const href = image.getAttribute('href') ?? '';
            const img = new Image();
            this.#imageElement.src = href;
            const that = this;
            img.onload = function () {
                let imageWidth, imageHeight;
                // @ts-expect-error
                [imageWidth, imageHeight, that.#originalWidth, that.#originalHeight] = [this.width, this.height, this.width, this.height];
                if (window.innerWidth < imageWidth) {
                    const aspectRatio = that.getAspectRatio(imageWidth, imageHeight);
                    imageWidth = window.innerWidth;
                    imageHeight = that.getHeight(imageWidth, aspectRatio);
                    imageWidth = that.getWidth(imageHeight, aspectRatio);
                }
                if (that.#imageElement != null) {
                    that.#imageElement.style.width = imageWidth + that.#pixel;
                    that.#imageElement.style.height = imageHeight + that.#pixel;
                }
            };
            img.src = href;
        }
    }
    /**
     * Update the title
     */
    updateTitle() {
        const title = document.getElementById('image-title');
        const image = this.#images[this.#currentImageIndex];
        if (title != null && image != null) {
            title.innerHTML = image.getAttribute('data-title') ?? '';
        }
    }
    /**
     * Update the image info
     */
    updateImageInfo() {
        const imageText = this.#settings.imageText;
        const ofText = this.#settings.ofText;
        const html = `${imageText} ${this.#currentImageIndex + 1} ${ofText} ${this.#numberOfImages}`;
        const imageInfo = document.getElementById('image-info');
        if (imageInfo != null) {
            imageInfo.innerHTML = html;
        }
    }
    /**
     * Show the previous link
     */
    showPreviousLink() {
        if (this.#previousLink != null) {
            this.#previousLink.classList.remove(this.#linkHiddenClass);
        }
    }
    /**
     * Hide the previous link
     */
    hidePreviousLink() {
        if (this.#previousLink != null) {
            this.#previousLink.classList.add(this.#linkHiddenClass);
        }
    }
    /**
     * Show the next link
     */
    showNextLink() {
        if (this.#nextLink != null) {
            this.#nextLink.classList.remove(this.#linkHiddenClass);
        }
    }
    /**
     * Hide the next link
     */
    hideNextLink() {
        if (this.#nextLink != null) {
            this.#nextLink.classList.add(this.#linkHiddenClass);
        }
    }
    /**
     * Show the overlay
     */
    showOverlay() {
        if (this.#galleryOverlay != null && this.#gallery != null) {
            this.#galleryOverlay.classList.add(this.#overlayVisibleClass);
            this.#gallery.classList.add(this.#galleryVisibleClass);
        }
    }
    /**
     * Hide the overlay
     */
    hideOverlay() {
        if (this.#galleryOverlay != null && this.#gallery != null) {
            this.#galleryOverlay.classList.remove(this.#overlayVisibleClass);
            this.#gallery.classList.remove(this.#galleryVisibleClass);
        }
    }
}
