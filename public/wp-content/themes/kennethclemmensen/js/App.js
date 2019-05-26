import { jQuery, lightbox } from './global';
import { SearchApp } from './SearchApp';
import { Slider } from './slider';
class App {
    constructor() {
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
                let slider = document.getElementById('slider');
                new Slider(slider.dataset.delay, slider.dataset.duration);
                let body = document.querySelector('body');
                lightbox.option({
                    'albumLabel': body.dataset.imageText + ' %1 ' + body.dataset.ofText + ' %2'
                });
                new SearchApp();
            });
        })(jQuery);
    }
}
new App();
