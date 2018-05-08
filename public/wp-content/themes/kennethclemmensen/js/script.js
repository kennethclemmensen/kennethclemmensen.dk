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

        class Slider {

            constructor(delay, duration) {
                this.delay = delay;
                this.duration = duration;
                this.$sliderImages = $('.slider__image');
            }

            getRandomNumber() {
                return Math.floor(Math.random() * this.$sliderImages.length);
            }

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