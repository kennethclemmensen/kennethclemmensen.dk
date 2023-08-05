import { fromEvent } from 'rxjs';
import { EventType } from './enums/EventType';
/**
 * The Gallery class contains methods to handle the functionality of the gallery
 */
export class Gallery {
    #template = `
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
	`;
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
    /**
     * Initialize a new instance of the Gallery class
     */
    constructor() {
        document.body.insertAdjacentHTML('beforeend', this.#template);
        this.#images = document.querySelectorAll('.page__gallery-thumbnail-link');
        this.#numberOfImages = this.#images.length;
        this.#currentImageIndex = 0;
        this.#linkHiddenClass = 'gallery__navigation-link--hidden';
        this.#overlayVisibleClass = 'gallery-overlay--visible';
        this.#galleryVisibleClass = 'gallery--visible';
        this.#previousLink = document.getElementById('previous-link');
        this.#nextLink = document.getElementById('next-link');
        this.#gallery = document.getElementById('gallery');
        this.#galleryOverlay = document.getElementById('gallery-overlay');
        this.#images.forEach((image) => {
            fromEvent(image, EventType.Click).subscribe((event) => {
                event.preventDefault();
                this.#currentImageIndex = parseInt(image.getAttribute('data-index') ?? '0');
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
                const galleryClose = document.getElementById('gallery-close');
                if (galleryClose != null) {
                    fromEvent(galleryClose, EventType.Click).subscribe((event) => {
                        event.preventDefault();
                        this.hideOverlay();
                    });
                }
                if (this.#galleryOverlay != null) {
                    fromEvent(this.#galleryOverlay, EventType.Click).subscribe((event) => {
                        event.preventDefault();
                        this.hideOverlay();
                    });
                }
            });
        });
        if (this.#previousLink != null) {
            fromEvent(this.#previousLink, EventType.Click).subscribe((event) => {
                event.preventDefault();
                this.#currentImageIndex--;
                this.showImage();
                this.updateTitle();
                this.updateImageInfo();
                this.showNextLink();
                if (this.#currentImageIndex === 0) {
                    this.hidePreviousLink();
                }
            });
        }
        if (this.#nextLink != null) {
            fromEvent(this.#nextLink, EventType.Click).subscribe((event) => {
                event.preventDefault();
                this.#currentImageIndex++;
                this.showImage();
                this.updateTitle();
                this.updateImageInfo();
                this.showPreviousLink();
                if ((this.#currentImageIndex + 1) === this.#numberOfImages) {
                    this.hideNextLink();
                }
            });
        }
    }
    /**
     * Show the image
     */
    showImage() {
        const image = this.#images[this.#currentImageIndex];
        if (image != null) {
            document.getElementById('image').src = image.getAttribute('href') ?? '';
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
        const imageText = document.body.dataset.imageText;
        const ofText = document.body.dataset.ofText;
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
        this.#previousLink?.classList.remove(this.#linkHiddenClass);
    }
    /**
     * Hide the previous link
     */
    hidePreviousLink() {
        this.#previousLink?.classList.add(this.#linkHiddenClass);
    }
    /**
     * Show the next link
     */
    showNextLink() {
        this.#nextLink?.classList.remove(this.#linkHiddenClass);
    }
    /**
     * Hide the next link
     */
    hideNextLink() {
        this.#nextLink?.classList.add(this.#linkHiddenClass);
    }
    /**
     * Show the overlay
     */
    showOverlay() {
        this.#galleryOverlay?.classList.add(this.#overlayVisibleClass);
        this.#gallery?.classList.add(this.#galleryVisibleClass);
    }
    /**
     * Hide the overlay
     */
    hideOverlay() {
        this.#galleryOverlay?.classList.remove(this.#overlayVisibleClass);
        this.#gallery?.classList.remove(this.#galleryVisibleClass);
    }
}
