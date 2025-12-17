import { test, expect, Page } from '@playwright/test';

/**
 * Authentication Helper
 *
 * Logs into WordPress admin dashboard using credentials from environment variables.
 *
 * @param page - Playwright Page object
 * @returns Promise that resolves when login is complete
 * @throws Will fail the test if login fails or admin bar is not visible
 */
async function loginToWordPress(page: Page): Promise<void> {
	// Navigate to login page
	await page.goto('/wp-login.php');

	// Fill in credentials from environment or use defaults
	const username = process.env.WP_USERNAME || 'admin';
	const password = process.env.WP_PASSWORD || 'password';

	await page.fill('#user_login', username);
	await page.fill('#user_pass', password);

	// Submit login form
	await page.click('#wp-submit');

	// Verify successful login by checking for admin bar
	await expect(
		page.locator('#wpadminbar'),
		'WordPress admin bar should be visible after successful login'
	).toBeVisible();
}

/**
 * Admin Settings Test Suite
 *
 * Tests for the Dynamic Online Services plugin settings page in WordPress admin.
 * Verifies settings page accessibility, form elements, save functionality, and validation.
 *
 * Prerequisites:
 * - WordPress installation must be running at WP_BASE_URL
 * - Plugin must be activated
 * - Valid admin credentials must be provided via environment variables
 */
test.describe('Admin Settings', () => {
	/**
	 * Before Each Test Hook
	 *
	 * Authenticates the user before each test to ensure fresh session
	 * and avoid test interdependencies.
	 */
	test.beforeEach(async ({ page }) => {
		await loginToWordPress(page);
	});

	/**
	 * Test: Settings Page Loads Successfully
	 *
	 * Verifies that the plugin settings page is accessible and displays
	 * the expected heading and form elements.
	 */
	/**
	 * Test: Settings Page Loads Successfully
	 *
	 * Verifies that the plugin settings page is accessible and displays
	 * the expected heading and React root.
	 */
	test('should verify Dynamic Online Services settings page loads', async ({
		page,
	}) => {
		// Navigate to plugin settings page
		await page.goto('/wp-admin/admin.php?page=dynamic-online-services');

		// Verify main heading is present and visible
		await expect(
			page.locator('h1', {
				hasText: 'Dynamic Services Settings',
			}),
			'Settings page should display main heading'
		).toBeVisible();

		// Verify React root exists
		await expect(
			page.locator('#dynos-settings-root'),
			'React root container should be present'
		).toBeVisible();

		// Verify save button exists (React button)
		await expect(
			page.locator('button.components-button.is-primary'),
			'Settings page should have a primary save button'
		).toBeVisible();
	});

	/**
	 * Test: Settings Form Elements
	 *
	 * Verifies that expected form elements are present on the settings page.
	 */
	test('should display settings tabs and fields', async ({ page }) => {
		// Navigate to settings page
		await page.goto('/wp-admin/admin.php?page=dynamic-online-services');

		// Check for tabs
		const tabs = page.locator('.components-tab-panel__tabs');
		await expect(tabs, 'Settings tabs should be visible').toBeVisible();

		// Verify specific tabs exist (based on Config)
		await expect(page.locator('button', { hasText: 'General' })).toBeVisible();
		await expect(page.locator('button', { hasText: 'Hero Section' })).toBeVisible();

		// Check for a field in the default tab (General)
		await expect(
			page.locator('input[name="service_post_type_slug"]'),
			'Service Post Type Slug input should be visible'
		).toBeVisible();
	});

	/**
	 * Test: Settings Page Navigation
	 *
	 * Verifies that the settings page is accessible from WordPress admin menu.
	 */
	test('should be accessible from admin menu', async ({ page }) => {
		// Start from WordPress dashboard
		await page.goto('/wp-admin/');

		// Look for menu item
		const menuItem = page.locator(
			'#adminmenu a[href*="dynamic-online-services"]'
		).first();

		// Verify menu item exists
		await expect(
			menuItem,
			'Plugin menu item should be visible in WordPress admin menu'
		).toBeVisible();

		// Click menu item
		await menuItem.click();

		// Verify we're on the correct page
		await expect(page).toHaveURL(/page=dynamic-online-services/);

		// Verify page loaded correctly
		await expect(
			page.locator('h1', {
				hasText: 'Dynamic Services Settings',
			})
		).toBeVisible();
	});

	/**
	 * Test: Settings Save Functionality
	 *
	 * Tests that settings can be saved and success message appears.
	 */
	test('should save settings successfully', async ({ page }) => {
		// Navigate to settings page
		await page.goto('/wp-admin/admin.php?page=dynamic-online-services');

		// Click save button
		await page.click('button.components-button.is-primary');

		// Wait for and verify success message (Snackbar)
		await expect(
			page.locator('.components-snackbar').first(),
			'Success message should appear after saving settings'
		).toBeVisible({ timeout: 10000 });

		await expect(
			page.locator('.components-snackbar'),
			'Success message content check'
		).toContainText('Settings saved successfully');
	});

	/**
	 * Test: Page Responsiveness
	 *
	 * Verifies that the settings page works correctly on different viewport sizes.
	 */
	test('should be responsive on mobile viewport', async ({ page }) => {
		// Set mobile viewport
		await page.setViewportSize({ width: 375, height: 667 });

		// Navigate to settings page
		await page.goto('/wp-admin/admin.php?page=dynamic-online-services');

		// Verify main heading is still visible
		await expect(
			page.locator('h1', {
				hasText: 'Dynamic Services Settings',
			})
		).toBeVisible();

		// Verify save button is visible
		await expect(
			page.locator('button.components-button.is-primary')
		).toBeVisible();
	});
});
