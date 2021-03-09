import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class PageService {

  constructor() { }

  public getPageTitle(): string {
    return 'Velkommen';
  }

  public getPageContent(): string {
    return `<p>Velkommen til min hjemmeside som jeg har lavet, mens jeg har gået på hovedforløbet, som webintegrator, i Odense. På denne side vil der blandt andet være links til opgaver, som jeg har lavet på hovedforløbet, og nogle scripts som du kan downloade og bruge på din hjemmeside.</p>
      <p>I løbet af årene har jeg brugt mange timer på at køre flere tusinde kilometer, på cykel, på de danske landeveje. Det har ført mig rundt omkring blandt andet til Skjern, Ringkøbing, Ikast, Varde, Bramming, Vejle, Gludsted, Silkeborg, Odense, Ribe og endda helt til Tyskland. På nogle af turene har jeg taget nogle billeder, som du kan se <a href="https://kennethclemmensen.dk/billeder/">her</a>.</p>`;
  }
}
