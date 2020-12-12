import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GalleryLillebaeltComponent } from './gallery-lillebaelt.component';

describe('GalleryLillebaeltComponent', () => {
  let component: GalleryLillebaeltComponent;
  let fixture: ComponentFixture<GalleryLillebaeltComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GalleryLillebaeltComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GalleryLillebaeltComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
