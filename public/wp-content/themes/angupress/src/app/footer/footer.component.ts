import { Component, OnInit } from '@angular/core';
import { FooterService } from './footer.service';

@Component({
  selector: 'app-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.less']
})
export class FooterComponent implements OnInit {

  public widgets: string[];

  constructor(private footerService: FooterService) {
    this.widgets = [];
  }

  ngOnInit(): void {
    this.widgets = this.footerService.getWidgets();
  }
}
