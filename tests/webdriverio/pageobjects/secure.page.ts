import { $ } from '@wdio/globals';
import Page from './page.ts';

/**
 * sub page containing specific selectors and methods for a specific page
 */
class SecurePage extends Page {
    /**
     * define selectors using getter methods
     */
    public get flashAlert(): ChainablePromiseElement {
        return $('.wrap');
    }
}

export default new SecurePage();