import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GalleryOestjyllandComponent } from './gallery-oestjylland.component';

describe('GalleryOestjyllandComponent', () => {
  let component: GalleryOestjyllandComponent;
  let fixture: ComponentFixture<GalleryOestjyllandComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GalleryOestjyllandComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GalleryOestjyllandComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
