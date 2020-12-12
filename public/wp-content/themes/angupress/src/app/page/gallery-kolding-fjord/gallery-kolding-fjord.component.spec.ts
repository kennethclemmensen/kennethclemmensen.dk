import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GalleryKoldingFjordComponent } from './gallery-kolding-fjord.component';

describe('GalleryKoldingFjordComponent', () => {
  let component: GalleryKoldingFjordComponent;
  let fixture: ComponentFixture<GalleryKoldingFjordComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GalleryKoldingFjordComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GalleryKoldingFjordComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
