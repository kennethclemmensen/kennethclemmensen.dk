import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GalleryStorebaeltComponent } from './gallery-storebaelt.component';

describe('GalleryStorebaeltComponent', () => {
  let component: GalleryStorebaeltComponent;
  let fixture: ComponentFixture<GalleryStorebaeltComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GalleryStorebaeltComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GalleryStorebaeltComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
