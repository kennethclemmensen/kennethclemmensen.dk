jQuery.noConflict();
(function($) {
    $(document).ready(function() {
        $('.header__nav-trigger').on('click', function(event) {
            event.preventDefault();
            $(this).toggleClass('header__nav-trigger--active');
            $('.mobile-nav').toggleClass('mobile-nav--active');
            $('html, body').toggleClass('show-mobile-nav');
        });
        $('.mobile-nav__arrow').on('click', function(event) {
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
                let self = this;
                setInterval(function() {
                    self.$sliderImages.eq(randomNumber).fadeOut(self.duration, function() {
                        self.$sliderImages.eq(randomNumber).hide(); //prevent display block on more than one image
                        randomNumber = self.getRandomNumber();
                        self.$sliderImages.eq(randomNumber).fadeIn(self.duration);
                    });
                }, this.delay);
            }
        }

        let $slider = $('#slider');
        let slider = new Slider($slider.data('delay'), $slider.data('duration'));
        slider.show();

        let $body = $('body');
        lightbox.option({
            'albumLabel': $body.data('image-text') + ' %1 ' + $body.data('of-text') + ' %2'
        });
    });
})(jQuery);