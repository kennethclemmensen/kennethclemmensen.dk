import { Injectable } from '@angular/core';
import { Page } from '../types/Page';

@Injectable({
  providedIn: 'root'
})
export class PageService {

  constructor() { }

  public getPage(): Page {
    let pageTitle: string = '';
    let pageContent: string = '';
    switch(location.pathname) {
      case '/':
        pageTitle = 'Velkommen';
        pageContent = `<p>Velkommen til min hjemmeside som jeg har lavet, mens jeg har gået på hovedforløbet, som webintegrator, i Odense. På denne side vil der blandt andet være links til opgaver, som jeg har lavet på hovedforløbet, og nogle scripts som du kan downloade og bruge på din hjemmeside.</p>
        <p>I løbet af årene har jeg brugt mange timer på at køre flere tusinde kilometer, på cykel, på de danske landeveje. Det har ført mig rundt omkring blandt andet til Skjern, Ringkøbing, Ikast, Varde, Bramming, Vejle, Gludsted, Silkeborg, Odense, Ribe og endda helt til Tyskland. På nogle af turene har jeg taget nogle billeder, som du kan se <a href="https://kennethclemmensen.dk/billeder/">her</a>.</p>`;
        break;
      case '/billeder':
        pageTitle = 'Billeder';
        pageContent = 'På denne side er der forskellige gallerier med en masse billeder i. Vælg et galleri for at se billederne.';
        break;
      default:
        pageTitle = 'Siden blev ikke fundet';
        pageContent = 'Siden du søgte efter blev ikke fundet. For at finde siden kan du kigge under <a href="/sitemap">Sitemap.</a>';
        break;
    }
    return { title: pageTitle, content: pageContent };
  }
}
