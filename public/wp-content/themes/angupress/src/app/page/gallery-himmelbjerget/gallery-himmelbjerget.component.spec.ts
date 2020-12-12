import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GalleryHimmelbjergetComponent } from './gallery-himmelbjerget.component';

describe('GalleryHimmelbjergetComponent', () => {
  let component: GalleryHimmelbjergetComponent;
  let fixture: ComponentFixture<GalleryHimmelbjergetComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GalleryHimmelbjergetComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GalleryHimmelbjergetComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
