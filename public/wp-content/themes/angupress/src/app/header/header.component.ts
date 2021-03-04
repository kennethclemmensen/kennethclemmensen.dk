import { Component, OnInit } from '@angular/core';
import { MenuItem } from '../types/MenuItem';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.less']
})
export class HeaderComponent implements OnInit {

  public menuItems: MenuItem[];

  public constructor() { 
    this.menuItems = [];
  }

  public ngOnInit(): void {
    this.menuItems = [{
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
