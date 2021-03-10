import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class FooterService {

  constructor() { }

  public getWidgets(): string[] {
    return [
      '<p>Kenneth Clemmensen</p>',
      '<a href="mailto:kenneth.clemmensen@gmail.com" target="_self"><i class="fas fa-envelope"></i></a>',
      '<a href="https://www.linkedin.com/in/kennethclemmensen" target="_blank"><i class="fab fa-linkedin-in"></i></a>',
      '<a href="https://github.com/kennethclemmensen?tab=repositories" target="_blank"><i class="fab fa-github"></i></a>'
    ];
  }
}
