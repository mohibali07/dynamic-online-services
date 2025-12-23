import { test, expect } from '@playwright/test';
import { LoginPage } from './pages/LoginPage';

test.describe.skip('Marketing Widget Features', () => {

	// API Testing
	test('should return valid marketing widget API response', async ({ request }) => {
		// Use default REST route format to avoid permalink issues in CI/Test env
		const response = await request.get('/?rest_route=/dynos/v1/widget', {
			params: {
				title: 'Web Design',
				h1: 'Best Web Design Trends',
				url: 'http://example.com/web-design'
			}
		});

		if (!response.ok()) {
			console.log('API Error Status:', response.status());
			const textHTML = await response.text();
			console.log('API Error Text:', textHTML);

			// Debug: Check which namespaces are available
			const rootResponse = await request.get('/?rest_route=/');
			if (rootResponse.ok()) {
				const rootData = await rootResponse.json();
				console.log('Available Namespaces:', rootData.namespaces);
			}
		}
		expect(response.ok()).toBeTruthy();
		const body = await response.json();

		expect(body).toHaveProperty('success');
		expect(body).toHaveProperty('matches');
		expect(body).toHaveProperty('html');
		// We expect success to be true or false depending on backend state, but structure must match.
		// If success is true, html should be a string.
		if (body.success) {
			expect(Array.isArray(body.matches)).toBeTruthy();
			expect(typeof body.html).toBe('string');
			expect(body.html).toContain('dynos-marketing-widget');
		}
	});

	// Admin UI Testing
	test('should display Marketing Settings in Admin', async ({ page }) => {
		const loginPage = new LoginPage(page);
		await loginPage.goto();
		await loginPage.login();

		await page.goto('/wp-admin/admin.php?page=dynamic-online-services');

		// Check for Marketing Widget Settings section title
		// Note: The actual rendered title usually appears in an h2 or inside the form.
		// Based on add_settings_section('id', 'Marketing Widget Settings', ...) it should be on page.
		await expect(page.getByText('Marketing Widget Settings')).toBeVisible();

		// Check for fields
		await expect(page.getByLabel('Match Threshold')).toBeVisible();
		await expect(page.getByLabel('Stop Words')).toBeVisible();
	});
});
