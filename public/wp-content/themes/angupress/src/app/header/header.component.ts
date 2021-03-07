import { Component, OnInit } from '@angular/core';
import { MenuItem } from '../types/MenuItem';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.less']
})
export class HeaderComponent implements OnInit {

  public menuItems: MenuItem[];
  public showMobileMenu: boolean;

  public constructor() { 
    this.menuItems = [];
    this.showMobileMenu = false;
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

  public toggleMobileMenu(event: Event): void {
    event.preventDefault();
    this.showMobileMenu = !this.showMobileMenu;
  }
}
