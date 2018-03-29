jQuery.noConflict();
(function($) {
    $(document).ready(function() {
        $('.header__nav-trigger').on('click', function(event) {
            event.preventDefault();
            $(this).toggleClass('header__nav-trigger--active');
            $('.mobile-nav').toggleClass('mobile-nav--active');
            $('html, body').toggleClass('show-mobile-nav');
        });
        $('.mobile-nav__arrow').on('click', function() {
            $(this).toggleClass('mobile-nav__arrow--rotated');
            $(this).parent().parent().find('.sub-menu').toggle();
        });

        function Slider(delay, duration) {
            let $sliderImages = $('.slider__image');
            let getRandomNumber = function() {
                return Math.floor(Math.random() * $sliderImages.length);
            };
            this.show = function() {
                let randomNumber = getRandomNumber();
                $sliderImages.eq(randomNumber).show();
                setInterval(function() {
                    $sliderImages.eq(randomNumber).fadeOut(duration, function() {
                        $sliderImages.eq(randomNumber).hide(); //prevent display block on more than one image
                        randomNumber = getRandomNumber();
                        $sliderImages.eq(randomNumber).fadeIn(duration);
                    });
                }, delay);
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