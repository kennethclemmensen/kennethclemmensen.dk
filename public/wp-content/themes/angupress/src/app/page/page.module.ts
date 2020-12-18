import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FrontPageComponent } from './front-page/front-page.component';
import { ImagesPageComponent } from './images-page/images-page.component';
import { MoviePageComponent } from './movie-page/movie-page.component';
import { GalleryFynComponent } from './gallery-fyn/gallery-fyn.component';
import { GalleryHimmelbjergetComponent } from './gallery-himmelbjerget/gallery-himmelbjerget.component';
import { GalleryKoldingComponent } from './gallery-kolding/gallery-kolding.component';
import { GalleryKoldingFjordComponent } from './gallery-kolding-fjord/gallery-kolding-fjord.component';
import { GalleryCopenhagenComponent } from './gallery-copenhagen/gallery-copenhagen.component';
import { GalleryLillebaeltComponent } from './gallery-lillebaelt/gallery-lillebaelt.component';
import { GallerySilkeborgComponent } from './gallery-silkeborg/gallery-silkeborg.component';
import { GalleryStorebaeltComponent } from './gallery-storebaelt/gallery-storebaelt.component';
import { GalleryTurenTilJyllandComponent } from './gallery-turen-til-jylland/gallery-turen-til-jylland.component';
import { GalleryTurenTilLangelandComponent } from './gallery-turen-til-langeland/gallery-turen-til-langeland.component';
import { GalleryTurenTilSkagenComponent } from './gallery-turen-til-skagen/gallery-turen-til-skagen.component';
import { GalleryTurenTilTysklandComponent } from './gallery-turen-til-tyskland/gallery-turen-til-tyskland.component';
import { GalleryOestjyllandComponent } from './gallery-oestjylland/gallery-oestjylland.component';
import { PhpPageComponent } from './php-page/php-page.component';
import { JavaPageComponent } from './java-page/java-page.component';
import { LinksPageComponent } from './links-page/links-page.component';
import { OtherLinksPageComponent } from './other-links-page/other-links-page.component';
import { AboutPageComponent } from './about-page/about-page.component';
import { SearchPageComponent } from './search-page/search-page.component';
import { SitemapPageComponent } from './sitemap-page/sitemap-page.component';
import { PageNotFoundComponent } from './page-not-found/page-not-found.component';

@NgModule({
  declarations: [FrontPageComponent, ImagesPageComponent, MoviePageComponent, GalleryFynComponent, GalleryHimmelbjergetComponent, GalleryKoldingComponent, GalleryKoldingFjordComponent, GalleryCopenhagenComponent, GalleryLillebaeltComponent, GallerySilkeborgComponent, GalleryStorebaeltComponent, GalleryTurenTilJyllandComponent, GalleryTurenTilLangelandComponent, GalleryTurenTilSkagenComponent, GalleryTurenTilTysklandComponent, GalleryOestjyllandComponent, PhpPageComponent, JavaPageComponent, LinksPageComponent, OtherLinksPageComponent, AboutPageComponent, SearchPageComponent, SitemapPageComponent, PageNotFoundComponent],
  imports: [
    CommonModule
  ]
})
export class PageModule { }