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
            $(this).parent().parent().next('.sub-menu').toggle();
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
        var slider = new Slider($slider.data('delay'), $slider.data('duration'));
        slider.show();
    });
})(jQuery);

lightbox.option({
    'albumLabel': 'Billede %1 af %2'
});

var app = new Vue({
    el: '#search-app',
    data: {
        searchString: '',
        results: []
    },
    watch: {
        searchString: function() {
            this.search(null);
        }
    },
    methods: {
        search: function(event) {
            if(event !== null) event.preventDefault();
            this.$http.post('/wp-json/kcapi/v1/search', {title: this.searchString}).then(function(response) {
                this.results = response.body;
            }, function() {
                this.results = [];
            });
        }
    }
});