import { Component, OnInit } from '@angular/core';
import { Page } from '../types/Page';
import { PageService } from './page.service';

@Component({
  selector: 'app-page',
  templateUrl: './page.component.html',
  styleUrls: ['./page.component.less']
})
export class PageComponent implements OnInit {

  public title: string;
  public content: string;

  constructor(private pageService: PageService) { 
    this.title = '';
    this.content = '';
  }

  ngOnInit(): void {
    const page: Page = this.pageService.getPage();
    this.title = page.title;
    this.content = page.content;
  }
}
