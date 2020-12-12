import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GalleryTurenTilTysklandComponent } from './gallery-turen-til-tyskland.component';

describe('GalleryTurenTilTysklandComponent', () => {
  let component: GalleryTurenTilTysklandComponent;
  let fixture: ComponentFixture<GalleryTurenTilTysklandComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GalleryTurenTilTysklandComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GalleryTurenTilTysklandComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
