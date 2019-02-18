import { jQuery, lightbox } from './variables';
jQuery.noConflict();
(function ($) {
    $(document).ready(function () {
        $('.header__nav-trigger').on('click', function (event) {
            event.preventDefault();
            $(this).toggleClass('header__nav-trigger--active');
            $('.mobile-nav').toggleClass('mobile-nav--active');
            $('html, body').toggleClass('show-mobile-nav');
        });
        $('.mobile-nav__arrow').on('click', function (event) {
            event.preventDefault();
            $(this).toggleClass('mobile-nav__arrow--rotated');
            $(this).parent().parent().find('.sub-menu').toggle();
        });
        /**
         * The Slider class contains methods to handle the functionality of the slider
         */
        class Slider {
            /**
             * Slider constructor
             *
             * @param delay the delay of a slide
             * @param duration the duration of a slide
             */
            constructor(delay, duration) {
                this.delay = delay;
                this.duration = duration;
                this.$sliderImages = $('.slider__image');
                this.show();
            }
            /**
             * Get a random number
             *
             * @returns {number} a random number
             */
            getRandomNumber() {
                return Math.floor(Math.random() * this.$sliderImages.length);
            }
            /**
             * Show the slider
             */
            show() {
                let randomNumber = this.getRandomNumber();
                this.$sliderImages.eq(randomNumber).show();
                setInterval(() => {
                    this.$sliderImages.eq(randomNumber).fadeOut(this.duration, () => {
                        this.$sliderImages.eq(randomNumber).hide(); //prevent display block on more than one image
                        randomNumber = this.getRandomNumber();
                        this.$sliderImages.eq(randomNumber).fadeIn(this.duration);
                    });
                }, this.delay);
            }
        }
        let slider = document.getElementById('slider');
        new Slider(slider.dataset.delay, slider.dataset.duration);
        let body = document.querySelector('body');
        lightbox.option({
            'albumLabel': body.dataset.imageText + ' %1 ' + body.dataset.ofText + ' %2'
        });
    });
})(jQuery);
