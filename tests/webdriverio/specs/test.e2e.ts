import { expect } from '@wdio/globals';
import LoginPage from '../pageobjects/login.page.ts';
import SecurePage from '../pageobjects/secure.page.ts';

describe('My Login application', () => {
    it('should login with valid credentials', async() => {
        await LoginPage.open();
        await LoginPage.login('TestUser', 'TestPassword');
        await expect(SecurePage.flashAlert).toBeExisting();
        await expect(SecurePage.flashAlert).toHaveText(expect.stringContaining('Dashboard'));
    });
});