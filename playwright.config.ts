import { defineConfig, devices } from '@playwright/test';

/**
 * Playwright Configuration for WordPress Plugin Testing
 *
 * This configuration sets up end-to-end testing for the Dynamic Online Services
 * WordPress plugin using Playwright test runner.
 *
 * @see {@link https://playwright.dev/docs/test-configuration | Playwright Test Configuration}
 *
 * Environment Variables:
 * - WP_BASE_URL: Base URL of WordPress installation (default: http://localhost:8888)
 * - WP_USERNAME: WordPress admin username (default: admin)
 * - WP_PASSWORD: WordPress admin password (default: password)
 * - CI: Set to 'true' when running in CI environment
 */
export default defineConfig({
	/**
	 * Directory containing E2E test files
	 */
	testDir: './tests/e2e',

	/**
	 * Run tests in files in parallel for faster execution
	 * Individual tests within a file run sequentially
	 */
	fullyParallel: true,

	/**
	 * Fail the build on CI if test.only is accidentally left in source code
	 * This prevents incomplete test suites from passing in CI
	 */
	forbidOnly: !!process.env.CI,

	/**
	 * Retry failed tests on CI to handle flaky tests
	 * Local runs don't retry to make debugging easier
	 */
	retries: process.env.CI ? 2 : 0,

	/**
	 * Limit parallel workers on CI to reduce resource usage
	 * Local development can use all available cores
	 */
	workers: process.env.CI ? 1 : undefined,

	/**
	 * Test timeout settings (in milliseconds)
	 * WordPress admin pages can be slow, especially with plugins
	 */
	timeout: 60000, // 60 seconds per test
	expect: {
		/**
		 * Maximum time for expect() assertions
		 * Increased for WordPress admin page interactions
		 */
		timeout: 10000, // 10 seconds
	},

	/**
	 * Reporter configuration
	 * HTML reporter generates detailed test reports
	 */
	reporter: 'html',

	/**
	 * Shared settings for all browser projects
	 * @see {@link https://playwright.dev/docs/api/class-testoptions | Test Options}
	 */
	use: {
		/**
		 * Base URL for navigation
		 * Can be overridden via WP_BASE_URL environment variable
		 */
		baseURL: process.env.WP_BASE_URL || 'http://localhost:8888',

		/**
		 * Collect trace on first retry to help debug flaky tests
		 * Traces can be viewed at trace.playwright.dev
		 */
		trace: 'on-first-retry',

		/**
		 * Capture screenshot on test failure for debugging
		 */
		screenshot: 'only-on-failure',

		/**
		 * Record video on first retry to diagnose issues
		 */
		video: 'retain-on-failure',

		/**
		 * Maximum time for individual actions (click, fill, etc.)
		 * WordPress admin can be slow to respond
		 */
		actionTimeout: 15000, // 15 seconds
	},

	/**
	 * Configure projects for major browsers
	 * Tests will run in all configured browsers
	 */
	projects: [
		{
			name: 'chromium',
			use: {
				...devices['Desktop Chrome'],
				/**
				 * Viewport size for desktop testing
				 */
				viewport: { width: 1920, height: 1080 },
			},
		},

		{
			name: 'firefox',
			use: {
				...devices['Desktop Firefox'],
				viewport: { width: 1920, height: 1080 },
			},
		},

		{
			name: 'webkit',
			use: {
				...devices['Desktop Safari'],
				viewport: { width: 1920, height: 1080 },
			},
		},
	],

	/**
	 * Web server configuration for local testing
	 * Uncomment if you want Playwright to start your local WordPress server
	 */
	// webServer: {
	// 	command: 'npm run start:wordpress',
	// 	port: 8888,
	// 	timeout: 120000, // 2 minutes for WordPress to start
	// 	reuseExistingServer: !process.env.CI,
	// },
});
