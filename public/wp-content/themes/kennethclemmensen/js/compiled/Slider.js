var __classPrivateFieldSet = (this && this.__classPrivateFieldSet) || function (receiver, privateMap, value) {
    if (!privateMap.has(receiver)) {
        throw new TypeError("attempted to set private field on non-instance");
    }
    privateMap.set(receiver, value);
    return value;
};
var __classPrivateFieldGet = (this && this.__classPrivateFieldGet) || function (receiver, privateMap) {
    if (!privateMap.has(receiver)) {
        throw new TypeError("attempted to get private field on non-instance");
    }
    return privateMap.get(receiver);
};
var _slides, _sliderImage, _currentRandomNumber;
import { SliderAnimation } from './enums/SliderAnimation';
/**
 * The Slider class contains methods to handle the functionality of the slider
 */
export class Slider {
    /**
     * Initialize a new instance of the Slider class
     */
    constructor() {
        _slides.set(this, void 0);
        _sliderImage.set(this, void 0);
        _currentRandomNumber.set(this, void 0);
        __classPrivateFieldSet(this, _slides, document.getElementsByClassName('slider__slide'));
        __classPrivateFieldSet(this, _sliderImage, document.getElementById('slider-image'));
        __classPrivateFieldSet(this, _currentRandomNumber, -1);
    }
    /**
     * Show the slides
     *
     * @param delay the delay between two slides
     * @param duration the duration of a slide
     * @param animation the animation for the slides
     */
    showSlides(delay, duration, animation) {
        var _a;
        let randomNumber = this.getRandomNumber();
        let name = 'data-slide-image';
        let backgroundImageUrl = (_a = __classPrivateFieldGet(this, _slides)[randomNumber]) === null || _a === void 0 ? void 0 : _a.getAttribute(name);
        if (!backgroundImageUrl)
            return;
        this.setBackgroundImage(backgroundImageUrl);
        let startKeyframes = this.getStartKeyframes(animation);
        let endKeyframes = this.getEndKeyframes(animation);
        setInterval(() => {
            if (__classPrivateFieldGet(this, _sliderImage)) {
                __classPrivateFieldGet(this, _sliderImage).animate(startKeyframes, {
                    duration: delay
                }).onfinish = () => {
                    var _a, _b;
                    randomNumber = this.getRandomNumber();
                    backgroundImageUrl = (_a = __classPrivateFieldGet(this, _slides)[randomNumber]) === null || _a === void 0 ? void 0 : _a.getAttribute(name);
                    if (backgroundImageUrl)
                        this.setBackgroundImage(backgroundImageUrl);
                    (_b = __classPrivateFieldGet(this, _sliderImage)) === null || _b === void 0 ? void 0 : _b.animate(endKeyframes, { duration: delay });
                };
            }
        }, duration);
    }
    /**
     * Get a random number between 0 and the number of slides minus 1
     *
     * @returns a random number
     */
    getRandomNumber() {
        let randomNumber = Math.floor(Math.random() * __classPrivateFieldGet(this, _slides).length);
        if (__classPrivateFieldGet(this, _currentRandomNumber) === randomNumber)
            return this.getRandomNumber();
        __classPrivateFieldSet(this, _currentRandomNumber, randomNumber);
        return __classPrivateFieldGet(this, _currentRandomNumber);
    }
    /**
     * Set a background image on the slider image
     *
     * @param backgroundImageUrl the background image url
     */
    setBackgroundImage(backgroundImageUrl) {
        if (__classPrivateFieldGet(this, _sliderImage))
            __classPrivateFieldGet(this, _sliderImage).style.backgroundImage = 'url("' + backgroundImageUrl + '")';
    }
    /**
     * Get the start keyframes based on the animation
     *
     * @param animation the animation
     * @return the start keyframes
     */
    getStartKeyframes(animation) {
        let startKeyframes = [];
        if (__classPrivateFieldGet(this, _sliderImage)) {
            let width = __classPrivateFieldGet(this, _sliderImage).clientWidth;
            let height = __classPrivateFieldGet(this, _sliderImage).clientHeight;
            let px = 'px';
            switch (animation) {
                case SliderAnimation.SlideDown:
                    startKeyframes = [{ backgroundPositionY: 0 }, { backgroundPositionY: height + px }];
                    break;
                case SliderAnimation.SlideLeft:
                    startKeyframes = [{ backgroundPositionX: 0 }, { backgroundPositionX: -width + px }];
                    break;
                case SliderAnimation.SlideRight:
                    startKeyframes = [{ backgroundPositionX: 0 }, { backgroundPositionX: width + px }];
                    break;
                case SliderAnimation.SlideUp:
                    startKeyframes = [{ backgroundPositionY: 0 }, { backgroundPositionY: -height + px }];
                    break;
                default:
                    startKeyframes = [{ opacity: 1 }, { opacity: 0 }];
                    break;
            }
        }
        return startKeyframes;
    }
    /**
     * Get the end keyframes based on the animation
     *
     * @param animation the animation
     * @return the end keyframes
     */
    getEndKeyframes(animation) {
        let endKeyframes = [];
        if (__classPrivateFieldGet(this, _sliderImage)) {
            let width = __classPrivateFieldGet(this, _sliderImage).clientWidth;
            let height = __classPrivateFieldGet(this, _sliderImage).clientHeight;
            let px = 'px';
            switch (animation) {
                case SliderAnimation.SlideDown:
                    endKeyframes = [{ backgroundPositionY: height + px }, { backgroundPositionY: 0 }];
                    break;
                case SliderAnimation.SlideLeft:
                    endKeyframes = [{ backgroundPositionX: -width + px }, { backgroundPositionX: 0 }];
                    break;
                case SliderAnimation.SlideRight:
                    endKeyframes = [{ backgroundPositionX: width + px }, { backgroundPositionX: 0 }];
                    break;
                case SliderAnimation.SlideUp:
                    endKeyframes = [{ backgroundPositionY: -height + px }, { backgroundPositionY: 0 }];
                    break;
                default:
                    endKeyframes = [{ opacity: 0 }, { opacity: 1 }];
                    break;
            }
        }
        return endKeyframes;
    }
}
_slides = new WeakMap(), _sliderImage = new WeakMap(), _currentRandomNumber = new WeakMap();
