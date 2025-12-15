import { test, expect } from '@playwright/test';

test.describe( 'Course Management', () => {
	test.beforeEach( async ( { page } ) => {
		// Login
		await page.goto( '/wp-login.php' );
		await page.fill( '#user_login', process.env.WP_USERNAME || 'admin' );
		await page.fill( '#user_pass', process.env.WP_PASSWORD || 'password' );
		await page.click( '#wp-submit' );
		await expect( page.locator( '#wpadminbar' ) ).toBeVisible();
	} );

	test( 'should create and publish a new course', async ( { page } ) => {
		// Navigate to Add New Course
		await page.goto( '/wp-admin/post-new.php?post_type=courses' );

		// Close welcome guide if present (common in WP)
		const welcomeGuide = page.locator(
			'button[aria-label="Close welcome guide"]'
		);
		if ( await welcomeGuide.isVisible() ) {
			await welcomeGuide.click();
		}

		// Enter title
		const title = `Test Course ${ Date.now() }`;
		await page.fill( '#post-title-0', title );

		// Enter content (using paragraph block)
		await page.click( '.block-editor-default-block-appender__content' );
		await page.keyboard.type( 'This is a test course description.' );

		// Publish
		await page.click( '.editor-post-publish-panel__toggle' );
		// Confirm publish (double check potentially needed)
		await page.click( '.editor-post-publish-button' );

		// Verify published message
		await expect(
			page.locator( '.components-snackbar', { hasText: 'published' } )
		).toBeVisible();

		// View post
		const viewPostLink = page.locator( '.components-snackbar a', {
			hasText: 'View Course',
		} );
		await viewPostLink.click();

		// Verify frontend
		await expect( page.locator( 'h1' ) ).toContainText( title );
	} );
} );
