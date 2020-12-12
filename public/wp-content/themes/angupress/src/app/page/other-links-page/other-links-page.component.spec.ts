import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OtherLinksPageComponent } from './other-links-page.component';

describe('OtherLinksPageComponent', () => {
  let component: OtherLinksPageComponent;
  let fixture: ComponentFixture<OtherLinksPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ OtherLinksPageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(OtherLinksPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
