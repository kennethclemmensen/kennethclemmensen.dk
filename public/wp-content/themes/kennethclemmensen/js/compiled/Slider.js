var __classPrivateFieldSet = (this && this.__classPrivateFieldSet) || function (receiver, state, value, kind, f) {
    if (kind === "m") throw new TypeError("Private method is not writable");
    if (kind === "a" && !f) throw new TypeError("Private accessor was defined without a setter");
    if (typeof state === "function" ? receiver !== state || !f : !state.has(receiver)) throw new TypeError("Cannot write private member to an object whose class did not declare it");
    return (kind === "a" ? f.call(receiver, value) : f ? f.value = value : state.set(receiver, value)), value;
};
var __classPrivateFieldGet = (this && this.__classPrivateFieldGet) || function (receiver, state, kind, f) {
    if (kind === "a" && !f) throw new TypeError("Private accessor was defined without a getter");
    if (typeof state === "function" ? receiver !== state || !f : !state.has(receiver)) throw new TypeError("Cannot read private member from an object whose class did not declare it");
    return kind === "m" ? f : kind === "a" ? f.call(receiver) : f ? f.value : state.get(receiver);
};
var _Slider_slides, _Slider_sliderImage, _Slider_currentRandomNumber;
import { SliderAnimation } from './enums/SliderAnimation';
/**
 * The Slider class contains methods to handle the functionality of the slider
 */
export class Slider {
    /**
     * Initialize a new instance of the Slider class
     */
    constructor() {
        _Slider_slides.set(this, void 0);
        _Slider_sliderImage.set(this, void 0);
        _Slider_currentRandomNumber.set(this, void 0);
        __classPrivateFieldSet(this, _Slider_slides, document.getElementsByClassName('slider__slide'), "f");
        __classPrivateFieldSet(this, _Slider_sliderImage, document.getElementById('slider-image'), "f");
        __classPrivateFieldSet(this, _Slider_currentRandomNumber, -1, "f");
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
        const name = 'data-slide-image';
        let backgroundImageUrl = (_a = __classPrivateFieldGet(this, _Slider_slides, "f")[randomNumber]) === null || _a === void 0 ? void 0 : _a.getAttribute(name);
        if (!backgroundImageUrl)
            return;
        this.setBackgroundImage(backgroundImageUrl);
        const startKeyframes = this.getStartKeyframes(animation);
        const endKeyframes = this.getEndKeyframes(animation);
        setInterval(() => {
            if (__classPrivateFieldGet(this, _Slider_sliderImage, "f")) {
                __classPrivateFieldGet(this, _Slider_sliderImage, "f").animate(startKeyframes, {
                    duration: delay
                }).onfinish = () => {
                    var _a, _b;
                    randomNumber = this.getRandomNumber();
                    backgroundImageUrl = (_a = __classPrivateFieldGet(this, _Slider_slides, "f")[randomNumber]) === null || _a === void 0 ? void 0 : _a.getAttribute(name);
                    if (backgroundImageUrl)
                        this.setBackgroundImage(backgroundImageUrl);
                    (_b = __classPrivateFieldGet(this, _Slider_sliderImage, "f")) === null || _b === void 0 ? void 0 : _b.animate(endKeyframes, { duration: delay });
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
        const randomNumber = Math.floor(Math.random() * __classPrivateFieldGet(this, _Slider_slides, "f").length);
        if (__classPrivateFieldGet(this, _Slider_currentRandomNumber, "f") === randomNumber)
            return this.getRandomNumber();
        __classPrivateFieldSet(this, _Slider_currentRandomNumber, randomNumber, "f");
        return __classPrivateFieldGet(this, _Slider_currentRandomNumber, "f");
    }
    /**
     * Set a background image on the slider image
     *
     * @param backgroundImageUrl the background image url
     */
    setBackgroundImage(backgroundImageUrl) {
        if (__classPrivateFieldGet(this, _Slider_sliderImage, "f"))
            __classPrivateFieldGet(this, _Slider_sliderImage, "f").style.backgroundImage = 'url("' + backgroundImageUrl + '")';
    }
    /**
     * Get the start keyframes based on the animation
     *
     * @param animation the animation
     * @return the start keyframes
     */
    getStartKeyframes(animation) {
        let startKeyframes = [];
        if (__classPrivateFieldGet(this, _Slider_sliderImage, "f")) {
            const width = __classPrivateFieldGet(this, _Slider_sliderImage, "f").clientWidth;
            const height = __classPrivateFieldGet(this, _Slider_sliderImage, "f").clientHeight;
            const px = 'px';
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
        if (__classPrivateFieldGet(this, _Slider_sliderImage, "f")) {
            const width = __classPrivateFieldGet(this, _Slider_sliderImage, "f").clientWidth;
            const height = __classPrivateFieldGet(this, _Slider_sliderImage, "f").clientHeight;
            const px = 'px';
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
_Slider_slides = new WeakMap(), _Slider_sliderImage = new WeakMap(), _Slider_currentRandomNumber = new WeakMap();
