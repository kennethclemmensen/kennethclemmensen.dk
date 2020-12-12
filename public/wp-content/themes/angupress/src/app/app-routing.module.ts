import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AboutPageComponent } from './page/about-page/about-page.component';
import { FrontPageComponent } from './page/front-page/front-page.component';
import { GalleryCopenhagenComponent } from './page/gallery-copenhagen/gallery-copenhagen.component';
import { GalleryFynComponent } from './page/gallery-fyn/gallery-fyn.component';
import { GalleryHimmelbjergetComponent } from './page/gallery-himmelbjerget/gallery-himmelbjerget.component';
import { GalleryKoldingFjordComponent } from './page/gallery-kolding-fjord/gallery-kolding-fjord.component';
import { GalleryKoldingComponent } from './page/gallery-kolding/gallery-kolding.component';
import { GalleryLillebaeltComponent } from './page/gallery-lillebaelt/gallery-lillebaelt.component';
import { GalleryOestjyllandComponent } from './page/gallery-oestjylland/gallery-oestjylland.component';
import { GallerySilkeborgComponent } from './page/gallery-silkeborg/gallery-silkeborg.component';
import { GalleryStorebaeltComponent } from './page/gallery-storebaelt/gallery-storebaelt.component';
import { GalleryTurenTilJyllandComponent } from './page/gallery-turen-til-jylland/gallery-turen-til-jylland.component';
import { GalleryTurenTilLangelandComponent } from './page/gallery-turen-til-langeland/gallery-turen-til-langeland.component';
import { GalleryTurenTilSkagenComponent } from './page/gallery-turen-til-skagen/gallery-turen-til-skagen.component';
import { GalleryTurenTilTysklandComponent } from './page/gallery-turen-til-tyskland/gallery-turen-til-tyskland.component';
import { ImagesPageComponent } from './page/images-page/images-page.component';
import { JavaPageComponent } from './page/java-page/java-page.component';
import { LinksPageComponent } from './page/links-page/links-page.component';
import { MoviePageComponent } from './page/movie-page/movie-page.component';
import { OtherLinksPageComponent } from './page/other-links-page/other-links-page.component';
import { PhpPageComponent } from './page/php-page/php-page.component';
import { SearchPageComponent } from './page/search-page/search-page.component';
import { SitemapPageComponent } from './page/sitemap-page/sitemap-page.component';

const routes: Routes = [
  { path: 'billeder', component: ImagesPageComponent },
  { path: 'billeder/fyn', component: GalleryFynComponent },
  { path: 'billeder/himmelbjerget', component: GalleryHimmelbjergetComponent },
  { path: 'billeder/kobenhavn', component: GalleryCopenhagenComponent },
  { path: 'billeder/kolding', component: GalleryKoldingComponent },
  { path: 'billeder/kolding-fjord', component: GalleryKoldingFjordComponent },
  { path: 'billeder/lillebaelt', component: GalleryLillebaeltComponent },
  { path: 'billeder/silkeborg', component: GallerySilkeborgComponent },
  { path: 'billeder/storebaelt', component: GalleryStorebaeltComponent },
  { path: 'billeder/turen-til-jylland', component: GalleryTurenTilJyllandComponent },
  { path: 'billeder/turen-til-langeland', component: GalleryTurenTilLangelandComponent },
  { path: 'billeder/turen-til-skagen', component: GalleryTurenTilSkagenComponent },
  { path: 'billeder/turen-til-tyskland', component: GalleryTurenTilTysklandComponent },
  { path: 'billeder/oestjylland', component: GalleryOestjyllandComponent },
  { path: 'film', component: MoviePageComponent },
  { path: 'php', component: PhpPageComponent },
  { path: 'java', component: JavaPageComponent },
  { path: 'links', component: LinksPageComponent },
  { path: 'links/andre', component: OtherLinksPageComponent },
  { path: 'om-mig', component: AboutPageComponent },
  { path: 'soeg', component: SearchPageComponent },
  { path: 'sitemap', component: SitemapPageComponent },
  { path: '**', component: FrontPageComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }