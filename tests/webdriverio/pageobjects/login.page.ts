import { $ } from '@wdio/globals';
import Page from './page.ts';

/**
 * sub page containing specific selectors and methods for a specific page
 */
class LoginPage extends Page {
    /**
     * define selectors using getter methods
     */
    public get inputUsername(): ChainablePromiseElement {
        return $('#user_login');
    }

    public get inputPassword(): ChainablePromiseElement {
        return $('#user_pass');
    }

    public get btnSubmit(): ChainablePromiseElement {
        return $('input[type="submit"]');
    }

    /**
     * a method to encapsule automation code to interact with the page
     * e.g. to login using username and password
     */
    public async login(username: string, password: string): Promise<void> {
        await this.inputUsername.setValue(username);
        await this.inputPassword.setValue(password);
        await this.btnSubmit.click();
    }

    /**
     * overwrite specific options to adapt it to page object
     */
    public override open(): Promise<string> {
        return super.open('login');
    }
}

export default new LoginPage();