import { SearchApp } from './SearchApp';
import { Slider } from './Slider';

/**
 * The App class contains methods to handle the functionality of the app
 */
class App {

    private body: any;

    /**
     * App constructor
     */
    public constructor() {
        this.setupApp();
    }

    /**
     * Setup the app
     */
    private setupApp(): void {
        document.addEventListener('DOMContentLoaded', (): void => {
            this.body = document.querySelector('body');
            this.setupMobileNavigation();
            this.setupDownloadLinks();
            let slider: any = document.getElementById('slider');
            new Slider(slider.dataset.delay, slider.dataset.duration);
            lightbox.option({
                'albumLabel': this.body.dataset.imageText + ' %1 ' + this.body.dataset.ofText + ' %2'
            });
            new SearchApp();
        });
    }

    /**
     * Setup the mobile navigation
     */
    private setupMobileNavigation(): void {
        let mobileMenuTrigger: any = document.getElementById('mobile-menu-trigger');
        let mobileMenu: any = document.getElementById('mobile-menu');
        let showMobileMenuClass: string = 'show-mobile-menu';
        mobileMenuTrigger.addEventListener('click', (event: Event): void => {
            event.preventDefault();
            mobileMenuTrigger.classList.toggle('header__nav-trigger--active');
            mobileMenu.classList.toggle('mobile-nav--active');
            document.getElementsByTagName('html')[0].classList.toggle(showMobileMenuClass);
            this.body.classList.toggle(showMobileMenuClass);
        });
        let mobileNavigationArrows: any = document.querySelectorAll('.mobile-nav__arrow');
        mobileNavigationArrows.forEach((arrow: any): void => {
            arrow.addEventListener('click', (event: Event): void => {
                event.preventDefault();
                arrow.classList.toggle('mobile-nav__arrow--rotated');
                let subMenu: any = arrow.parentNode.parentNode.getElementsByClassName('sub-menu')[0];
                subMenu.style.display = (subMenu.style.display === 'block') ? 'none' : 'block';
            });
        });
    }

    /**
     * Setup the download links
     */
    private setupDownloadLinks(): void {
        jQuery('.fdwc__link').on('click', function() {
            let $this = jQuery(this);
            jQuery.ajax({
                method: 'post',
                dataType: 'json',
                url: '/wp-admin/admin-ajax.php',
                data: {
                    action: 'fdwc_download',
                    file_id: $this.data('file-id')
                },
                success: function(data) {
                    $this.parent().find('.fdwc__downloads').html(data);
                }
            });
        });
    }
}

new App();