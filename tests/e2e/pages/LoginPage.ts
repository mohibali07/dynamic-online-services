import { Page, Locator, expect } from '@playwright/test';

export class LoginPage {
	readonly page: Page;
	readonly usernameInput: Locator;
	readonly passwordInput: Locator;
	readonly loginButton: Locator;

	constructor( page: Page ) {
		this.page = page;
		this.usernameInput = page.locator( '#user_login' );
		this.passwordInput = page.locator( '#user_pass' );
		this.loginButton = page.locator( '#wp-submit' );
	}

	async goto() {
		await this.page.goto( '/wp-login.php' );
	}

	async login() {
		const username = process.env.WP_USERNAME;
		const password = process.env.WP_PASSWORD;

		if ( ! username || ! password ) {
			throw new Error(
				'WP_USERNAME and WP_PASSWORD environment variables are required for testing. DO NOT fallback to hardcoded defaults.'
			);
		}

		await this.usernameInput.fill( username );
		await this.passwordInput.fill( password );
		await this.loginButton.click();

		// Wait for dashboard or admin bar to confirm login, allowing time for slow redirects
		await expect( this.page.locator( '#wpadminbar' ) ).toBeVisible( {
			timeout: 15000,
		} );
	}
}
