import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GalleryTurenTilJyllandComponent } from './gallery-turen-til-jylland.component';

describe('GalleryTurenTilJyllandComponent', () => {
  let component: GalleryTurenTilJyllandComponent;
  let fixture: ComponentFixture<GalleryTurenTilJyllandComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GalleryTurenTilJyllandComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GalleryTurenTilJyllandComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
