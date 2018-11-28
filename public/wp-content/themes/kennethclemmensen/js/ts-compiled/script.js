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
new Vue({
    el: '#search-app',
    data: {
        searchString: '',
        results: []
    },
    watch: {
        searchString: function () {
            this.search();
        }
    },
    methods: {
        search: function () {
            if (this.searchString === '') {
                this.results = [];
                return;
            }
            let self = this;
            let responseCodeOk = 200;
            let request = new XMLHttpRequest();
            request.open('get', '/wp-json/kcapi/v1/pages/' + this.searchString, true);
            request.addEventListener('load', () => {
                self.results = (request.status === responseCodeOk) ? JSON.parse(request.responseText) : [];
            });
            request.addEventListener('error', () => {
                self.results = [];
            });
            request.send();
        }
    },
    components: {
        'search-results': {
            data: function () {
                return {
                    offset: 0,
                    perPage: 5
                };
            },
            methods: {
                previousPage: function () {
                    this.offset -= this.perPage;
                },
                nextPage: function () {
                    this.offset += this.perPage;
                }
            },
            props: {
                results: {
                    required: true,
                    type: Array
                },
                previousText: {
                    required: true,
                    type: String
                },
                nextText: {
                    required: true,
                    type: String
                }
            },
            watch: {
                results: function () {
                    this.offset = 0;
                }
            },
            template: `
                <div>
                    <div v-for="result in results.slice(offset, (offset + perPage))" :key="result.id">
                        <a :href="result.link">{{ result.title }}</a>
                        <p>{{ result.excerpt }}</p>
                    </div>
                    <div>
                        <a href="#" @click.prevent="previousPage" v-if="offset > 0">{{ previousText }}</a>
                        <a href="#" @click.prevent="nextPage" v-if="offset < (results.length - perPage)">{{ nextText }}</a>
                    </div>
                </div>
            `
        }
    }
});
