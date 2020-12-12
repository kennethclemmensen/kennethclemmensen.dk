import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GalleryCopenhagenComponent } from './gallery-copenhagen.component';

describe('GalleryCopenhagenComponent', () => {
  let component: GalleryCopenhagenComponent;
  let fixture: ComponentFixture<GalleryCopenhagenComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GalleryCopenhagenComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GalleryCopenhagenComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
