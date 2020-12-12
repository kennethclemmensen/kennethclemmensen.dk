import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GalleryTurenTilSkagenComponent } from './gallery-turen-til-skagen.component';

describe('GalleryTurenTilSkagenComponent', () => {
  let component: GalleryTurenTilSkagenComponent;
  let fixture: ComponentFixture<GalleryTurenTilSkagenComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GalleryTurenTilSkagenComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GalleryTurenTilSkagenComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
