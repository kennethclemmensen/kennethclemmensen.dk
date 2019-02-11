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
        class Slider {
            constructor(delay, duration) {
                this.delay = delay;
                this.duration = duration;
                this.$sliderImages = $('.slider__image');
                this.show();
            }
            getRandomNumber() {
                return Math.floor(Math.random() * this.$sliderImages.length);
            }
            show() {
                let randomNumber = this.getRandomNumber();
                this.$sliderImages.eq(randomNumber).show();
                setInterval(() => {
                    this.$sliderImages.eq(randomNumber).fadeOut(this.duration, () => {
                        this.$sliderImages.eq(randomNumber).hide();
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
