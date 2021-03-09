import { Component, OnInit } from '@angular/core';
import { SliderService } from './slider.service';

@Component({
  selector: 'app-slider',
  templateUrl: './slider.component.html',
  styleUrls: ['./slider.component.less']
})
export class SliderComponent implements OnInit {

  private slides: string[];
  private sliderImage: HTMLElement | null;
  private currentRandomNumber: number;

  public constructor(private sliderService: SliderService) {
    this.slides = [];
    this.sliderImage = null;
    this.currentRandomNumber = -1;
  }

  public ngOnInit(): void {
    this.slides = this.sliderService.getSlides();
    this.sliderImage = document.getElementById('slider-image');
    this.showSlides();
  }

  /**
   * Show the slides
   */
  private showSlides(): void {
    let delay: number = 500;
    let duration: number = 8000;
    let randomNumber: number = this.getRandomNumber();
    let backgroundImageUrl: string | null = this.slides[randomNumber];
    if (!backgroundImageUrl) return;
    this.setBackgroundImage(backgroundImageUrl);
    let startKeyframes: Keyframe[] = [{ opacity: 1 }, { opacity: 0 }];
    let endKeyframes: Keyframe[] = [{ opacity: 0 }, { opacity: 1 }];
    setInterval((): void => {
      if (this.sliderImage) {
        this.sliderImage.animate(startKeyframes, {
          duration: delay
        }).onfinish = (): void => {
          randomNumber = this.getRandomNumber();
          backgroundImageUrl = this.slides[randomNumber];
          if (backgroundImageUrl) this.setBackgroundImage(backgroundImageUrl);
          this.sliderImage?.animate(endKeyframes, { duration: delay });
        };
      }
    }, duration);
  }

  /**
   * Get a random number between 0 and the number of slides minus 1
   * 
   * @returns a random number
   */
  private getRandomNumber(): number {
    let randomNumber: number = Math.floor(Math.random() * this.slides.length);
    if (this.currentRandomNumber === randomNumber) return this.getRandomNumber();
    this.currentRandomNumber = randomNumber;
    return this.currentRandomNumber;
  }

  /**
   * Set a background image on the slider image
   * 
   * @param backgroundImageUrl the background image url
   */
  private setBackgroundImage(backgroundImageUrl: string): void {
    if (this.sliderImage) this.sliderImage.style.backgroundImage = 'url("' + backgroundImageUrl + '")';
  }
}