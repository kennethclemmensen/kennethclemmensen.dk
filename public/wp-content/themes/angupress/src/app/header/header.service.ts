import { Injectable } from '@angular/core';
import { MenuItem } from '../types/MenuItem';

@Injectable({
  providedIn: 'root'
})
export class HeaderService {

  constructor() { }

  public getMenuItems(): MenuItem[] {
    return [{
      url: '/',
      title: 'Forside'
    }, {
      url: '/billeder',
      title: 'Billeder'
    }, {
      url: '/film',
      title: 'Film'
    }, {
      url: '/php',
      title: 'PHP'
    }, {
      url: '/java',
      title: 'Java'
    }, {
      url: '/links',
      title: 'Links'
    }, {
      url: '/om-mig',
      title: 'Om mig'
    }, {
      url: '/soeg',
      title: 'Søg'
    }, {
      url: '/sitemap',
      title: 'Sitemap'
    }];
  }
}
