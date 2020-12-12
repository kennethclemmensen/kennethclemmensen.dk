import { ComponentFixture, TestBed } from '@angular/core/testing';

import { GallerySilkeborgComponent } from './gallery-silkeborg.component';

describe('GallerySilkeborgComponent', () => {
  let component: GallerySilkeborgComponent;
  let fixture: ComponentFixture<GallerySilkeborgComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ GallerySilkeborgComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(GallerySilkeborgComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
