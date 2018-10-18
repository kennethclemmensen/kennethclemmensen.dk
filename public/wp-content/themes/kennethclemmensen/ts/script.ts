declare let jQuery: any;
declare let lightbox: any;

jQuery.noConflict();
(function($): void {
    $(document).ready(function(): void {
        $('.header__nav-trigger').on('click', function(event: Event): void {
            event.preventDefault();
            $(this).toggleClass('header__nav-trigger--active');
            $('.mobile-nav').toggleClass('mobile-nav--active');
            $('html, body').toggleClass('show-mobile-nav');
        });
        $('.mobile-nav__arrow').on('click', function(event: Event): void {
            event.preventDefault();
            $(this).toggleClass('mobile-nav__arrow--rotated');
            $(this).parent().parent().find('.sub-menu').toggle();
        });

        /**
         * The Slider class contains methods to handle the functionality of the slider
         */
        class Slider {

            private delay: number;
            private duration: number;
            private $sliderImages: any;

            /**
             * Slider constructor
             *
             * @param delay the delay of a slide
             * @param duration the duration of a slide
             */
            public constructor(delay: number, duration: number) {
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
            private getRandomNumber(): number {
                return Math.floor(Math.random() * this.$sliderImages.length);
            }

            /**
             * Show the slider
             */
            private show(): void {
                let randomNumber: number = this.getRandomNumber();
                this.$sliderImages.eq(randomNumber).show();
                let self: any = this;
                setInterval(function(): void {
                    self.$sliderImages.eq(randomNumber).fadeOut(self.duration, function(): void {
                        self.$sliderImages.eq(randomNumber).hide(); //prevent display block on more than one image
                        randomNumber = self.getRandomNumber();
                        self.$sliderImages.eq(randomNumber).fadeIn(self.duration);
                    });
                }, this.delay);
            }
        }

        let slider: any = document.getElementById('slider');
        new Slider(slider.dataset.delay, slider.dataset.duration);

        let body: any = document.querySelector('body');
        lightbox.option({
            'albumLabel': body.dataset.imageText + ' %1 ' + body.dataset.ofText + ' %2'
        });
    });
})(jQuery);