import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GalleryFynComponent } from './gallery-fyn.component';

describe('GalleryFynComponent', () => {
  let component: GalleryFynComponent;
  let fixture: ComponentFixture<GalleryFynComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GalleryFynComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GalleryFynComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
