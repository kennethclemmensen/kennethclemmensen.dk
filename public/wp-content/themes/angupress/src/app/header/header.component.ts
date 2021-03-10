import { Component, OnInit } from '@angular/core';
import { MenuItem } from '../types/MenuItem';
import { HeaderService } from './header.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.less']
})
export class HeaderComponent implements OnInit {

  public menuItems: MenuItem[];
  public showMobileMenu: boolean;

  public constructor(private headerService: HeaderService) { 
    this.menuItems = [];
    this.showMobileMenu = false;
  }

  public ngOnInit(): void {
    this.menuItems = this.headerService.getMenuItems();
  }

  public toggleMobileMenu(event: Event): void {
    event.preventDefault();
    this.showMobileMenu = !this.showMobileMenu;
  }
}
