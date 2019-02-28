import { jQuery, lightbox } from './global';
import { Slider } from './slider';

jQuery.noConflict();
(function($: any): void {
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

        let slider: any = document.getElementById('slider');
        new Slider(slider.dataset.delay, slider.dataset.duration);

        let body: any = document.querySelector('body');
        lightbox.option({
            'albumLabel': body.dataset.imageText + ' %1 ' + body.dataset.ofText + ' %2'
        });
    });
})(jQuery);