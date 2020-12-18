import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HeaderComponent } from './header/header.component';
import { FooterComponent } from './footer/footer.component';
import { SliderComponent } from './slider/slider.component';
import { BreadcrumbComponent } from './breadcrumb/breadcrumb.component';
import { FrontPageComponent } from './page/front-page/front-page.component';
import { ImagesPageComponent } from './page/images-page/images-page.component';
import { MoviePageComponent } from './page/movie-page/movie-page.component';
import { GalleryFynComponent } from './page/gallery-fyn/gallery-fyn.component';
import { GalleryHimmelbjergetComponent } from './page/gallery-himmelbjerget/gallery-himmelbjerget.component';
import { GalleryKoldingComponent } from './page/gallery-kolding/gallery-kolding.component';
import { GalleryKoldingFjordComponent } from './page/gallery-kolding-fjord/gallery-kolding-fjord.component';
import { GalleryCopenhagenComponent } from './page/gallery-copenhagen/gallery-copenhagen.component';
import { GalleryLillebaeltComponent } from './page/gallery-lillebaelt/gallery-lillebaelt.component';
import { GallerySilkeborgComponent } from './page/gallery-silkeborg/gallery-silkeborg.component';
import { GalleryStorebaeltComponent } from './page/gallery-storebaelt/gallery-storebaelt.component';
import { GalleryTurenTilJyllandComponent } from './page/gallery-turen-til-jylland/gallery-turen-til-jylland.component';
import { GalleryTurenTilLangelandComponent } from './page/gallery-turen-til-langeland/gallery-turen-til-langeland.component';
import { GalleryTurenTilSkagenComponent } from './page/gallery-turen-til-skagen/gallery-turen-til-skagen.component';
import { GalleryTurenTilTysklandComponent } from './page/gallery-turen-til-tyskland/gallery-turen-til-tyskland.component';
import { GalleryOestjyllandComponent } from './page/gallery-oestjylland/gallery-oestjylland.component';
import { PhpPageComponent } from './page/php-page/php-page.component';
import { JavaPageComponent } from './page/java-page/java-page.component';
import { LinksPageComponent } from './page/links-page/links-page.component';
import { OtherLinksPageComponent } from './page/other-links-page/other-links-page.component';
import { AboutPageComponent } from './page/about-page/about-page.component';
import { SearchPageComponent } from './page/search-page/search-page.component';
import { SitemapPageComponent } from './page/sitemap-page/sitemap-page.component';
import { PageNotFoundComponent } from './page/page-not-found/page-not-found.component';

@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
    FooterComponent,
    SliderComponent,
    BreadcrumbComponent,
    FrontPageComponent,
    ImagesPageComponent,
    MoviePageComponent,
    GalleryFynComponent,
    GalleryHimmelbjergetComponent,
    GalleryKoldingComponent,
    GalleryKoldingFjordComponent,
    GalleryCopenhagenComponent,
    GalleryLillebaeltComponent,
    GallerySilkeborgComponent,
    GalleryStorebaeltComponent,
    GalleryTurenTilJyllandComponent,
    GalleryTurenTilLangelandComponent,
    GalleryTurenTilSkagenComponent,
    GalleryTurenTilTysklandComponent,
    GalleryOestjyllandComponent,
    PhpPageComponent,
    JavaPageComponent,
    LinksPageComponent,
    OtherLinksPageComponent,
    AboutPageComponent,
    SearchPageComponent,
    SitemapPageComponent,
    PageNotFoundComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }