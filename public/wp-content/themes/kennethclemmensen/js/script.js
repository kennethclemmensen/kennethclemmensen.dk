jQuery.noConflict();
(function($) {
    $(document).ready(function() {
        $('.header__nav-trigger').on('click', function(event) {
            event.preventDefault();
            $(this).toggleClass('header__nav-trigger--active');
            $('.mobile-nav').toggleClass('mobile-nav--active');
            $('html, body').toggleClass('show-mobile-nav');
        });
        function Slider(delay, duration) {
            var sliderImages = $('.slider__image');
            var getRandomNumber = function() {
                return Math.floor(Math.random() * sliderImages.length);
            };
            this.show = function() {
                var randomNumber = getRandomNumber();
                sliderImages.eq(randomNumber).show();
                setInterval(function() {
                    sliderImages.eq(randomNumber).fadeOut(duration, function() {
                        sliderImages.css('display', 'none'); //prevent display block on more than one image
                        randomNumber = getRandomNumber();
                        sliderImages.eq(randomNumber).fadeIn(duration);
                    });
                }, delay);
            }
        }

        var $slider = $('#slider');
        var delay = $slider.data('delay');
        var duration = $slider.data('duration');
        var slider = new Slider(delay, duration);
        slider.show();
    });
})(jQuery);

lightbox.option({
    'albumLabel': 'Billede %1 af %2'
});

var app = new Vue({
    el: '#search-form',
    data: {
        searchString: ''
    },
    watch: {
        searchString: function() {
            this.search(null);
        }
    },
    methods: {
        search: function(event) {
            if(event !== null) event.preventDefault();
        }
    }
});