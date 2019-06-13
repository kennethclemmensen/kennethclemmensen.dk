export class Slider {
    constructor(delay, duration) {
        this.delay = delay;
        this.duration = duration;
        this.sliderImages = jQuery('.slider__image');
        this.showSlider();
    }
    getRandomNumber() {
        return Math.floor(Math.random() * this.sliderImages.length);
    }
    showSlider() {
        let randomNumber = this.getRandomNumber();
        this.sliderImages.eq(randomNumber).show();
        setInterval(() => {
            this.sliderImages.eq(randomNumber).fadeOut(this.duration, () => {
                this.sliderImages.eq(randomNumber).hide();
                randomNumber = this.getRandomNumber();
                this.sliderImages.eq(randomNumber).fadeIn(this.duration);
            });
        }, this.delay);
    }
}
