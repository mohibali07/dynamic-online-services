import { test, expect } from '@playwright/test';

test.describe( 'Admin Settings', () => {
	test.beforeEach( async ( { page } ) => {
		// Login
		await page.goto( '/wp-login.php' );
		await page.fill( '#user_login', process.env.WP_USERNAME || 'admin' );
		await page.fill( '#user_pass', process.env.WP_PASSWORD || 'password' );
		await page.click( '#wp-submit' );
		await expect( page.locator( '#wpadminbar' ) ).toBeVisible();
	} );

	test( 'should verify Dynamic Online Services settings page loads', async ( {
		page,
	} ) => {
		// Navigate to settings
		await page.goto( '/wp-admin/admin.php?page=dynamic-online-services' );

		// Check for main heading
		await expect(
			page.locator( 'h1', {
				hasText: 'Dynamic Online Services Settings',
			} )
		).toBeVisible();

		// Check for some expected elements (based on typical settings pages)
		// Adjust selector based on actual implementation
		await expect( page.locator( 'button[type="submit"]' ) ).toBeVisible();
	} );
} );
