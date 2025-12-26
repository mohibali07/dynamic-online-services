import { test, expect } from '@playwright/test';
import AxeBuilder from '@axe-core/playwright';
import { LoginPage } from './pages/LoginPage';

test.describe( 'Admin Settings', () => {
	let loginPage: LoginPage;

	test.beforeEach( async ( { page } ) => {
		loginPage = new LoginPage( page );

		// Login
		await loginPage.goto();
		await loginPage.login();
	} );

	test( 'should verify Dynamic Online Services settings page loads and is accessible', async ( {
		page,
	} ) => {
		// Navigate to settings
		await page.goto( '/wp-admin/admin.php?page=dynamic-online-services' );

		// Check for main heading
		await expect(
			page.getByRole( 'heading', {
				name: 'DynOS',
				level: 1,
			} )
		).toBeVisible();

		// Accessibility check
		const accessibilityScanResults = await new AxeBuilder( { page } )
			.exclude( '#wpadminbar' ) // Exclude WP admin bar
			.exclude( '#adminmenumain' ) // Exclude WP admin menu
			.exclude( '#wpbody' ) // Exclude WP core content wrapper (landmark-unique violation)
			.exclude( '#screen-meta' ) // Exclude WP screen options
			.exclude( '#adminmenuwrap' ) // Exclude admin menu wrapper
			.analyze();

		expect( accessibilityScanResults.violations ).toEqual( [] );

		// Check for submit button
		await expect(
			page.getByRole( 'button', { name: 'Save Changes' } )
		).toBeVisible();
	} );
} );
