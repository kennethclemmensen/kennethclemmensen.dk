import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GalleryKoldingComponent } from './gallery-kolding.component';

describe('GalleryKoldingComponent', () => {
  let component: GalleryKoldingComponent;
  let fixture: ComponentFixture<GalleryKoldingComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GalleryKoldingComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GalleryKoldingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
