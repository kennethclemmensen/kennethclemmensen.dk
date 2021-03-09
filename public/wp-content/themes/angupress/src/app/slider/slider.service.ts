import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class SliderService {

  constructor() { }

  public getSlides(): string[] {
    return [
      'assets/images/image_1.jpg',
      'assets/images/image_2.jpg'
    ];
  }
}
