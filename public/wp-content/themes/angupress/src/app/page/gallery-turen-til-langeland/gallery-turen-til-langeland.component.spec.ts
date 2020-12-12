import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GalleryTurenTilLangelandComponent } from './gallery-turen-til-langeland.component';

describe('GalleryTurenTilLangelandComponent', () => {
  let component: GalleryTurenTilLangelandComponent;
  let fixture: ComponentFixture<GalleryTurenTilLangelandComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GalleryTurenTilLangelandComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GalleryTurenTilLangelandComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
