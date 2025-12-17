import { test, expect, Page } from '@playwright/test';

/**
 * Test Course Data Interface
 *
 * Defines the structure for course test data used in E2E tests.
 */
interface CourseTestData {
	/** Course title with unique timestamp */
	title: string;
	/** Course description/content */
	content: string;
	/** Post ID (set after creation) */
	postId?: number;
}

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
 * Cleanup Helper: Delete Test Course
 *
 * Deletes a course post by moving it to trash.
 * Used in afterEach hooks to clean up test data.
 *
 * @param page - Playwright Page object
 * @param postId - ID of the post to delete
 */
async function deleteTestCourse(page: Page, postId: number): Promise<void> {
	try {
		// Navigate to edit page
		await page.goto(`/wp-admin/post.php?post=${postId}&action=edit`);

		// Move to trash
		const trashLink = page.locator('#delete-action a.submitdelete');
		if (await trashLink.isVisible()) {
			await trashLink.click();

			// Wait for redirect to posts list
			await expect(page).toHaveURL(/post_type=courses/);
		}
	} catch (error) {
		// Log error but don't fail test cleanup
		console.warn(`Failed to delete test course ${postId}:`, error);
	}
}

/**
 * Helper: Extract Post ID from URL
 *
 * Extracts the WordPress post ID from the current page URL.
 *
 * @param page - Playwright Page object
 * @returns Post ID or null if not found
 */
async function getPostIdFromUrl(page: Page): Promise<number | null> {
	const url = page.url();
	const match = url.match(/[?&]post=(\d+)/);
	return match ? parseInt(match[1], 10) : null;
}

/**
 * Course Management Test Suite
 *
 * Comprehensive E2E tests for course custom post type functionality.
 * Tests course creation, editing, publishing, listing, and deletion.
 *
 * Prerequisites:
 * - WordPress installation must be running at WP_BASE_URL
 * - Dynamic Online Services plugin must be activated
 * - Valid admin credentials must be provided via environment variables
 * - Course post type must be registered
 */
test.describe('Course Management', () => {
	/**
	 * Stores created course data for cleanup
	 */
	let createdCourseId: number | null = null;

	/**
	 * Before Each Test Hook
	 *
	 * Authenticates the user before each test to ensure fresh session.
	 */
	test.beforeEach(async ({ page }) => {
		await loginToWordPress(page);
	});

	/**
	 * After Each Test Hook
	 *
	 * Cleans up any courses created during testing to avoid database pollution.
	 */
	test.afterEach(async ({ page }) => {
		if (createdCourseId) {
			await deleteTestCourse(page, createdCourseId);
			createdCourseId = null;
		}
	});

	/**
	 * Test: Create and Publish New Course
	 *
	 * Verifies the complete workflow of creating and publishing a new course,
	 * including title, content, and frontend display.
	 */
	test('should create and publish a new course', async ({ page }) => {
		// Prepare test data
		const courseData: CourseTestData = {
			title: `Test Course ${Date.now()}`,
			content: 'This is a test course description with detailed information.',
		};

		// Navigate to Add New Course page
		await page.goto('/wp-admin/post-new.php?post_type=courses');

		// Close welcome guide if present (common in WordPress block editor)
		const welcomeGuide = page.locator(
			'button[aria-label="Close welcome guide"]'
		);
		if (await welcomeGuide.isVisible()) {
			await welcomeGuide.click();
		}

		// Enter course title
		await page.fill('#post-title-0', courseData.title);
		await expect(
			page.locator('#post-title-0'),
			'Title should be filled correctly'
		).toHaveValue(courseData.title);

		// Enter course content using the block editor
		await page.click('.block-editor-default-block-appender__content');
		await page.keyboard.type(courseData.content);

		// Publish the course
		await page.click('.editor-post-publish-panel__toggle');

		// Confirm publish (double-check button might be needed)
		await page.click('.editor-post-publish-button');

		// Verify published success message
		await expect(
			page.locator('.components-snackbar', { hasText: 'published' }),
			'Course should be published successfully'
		).toBeVisible({ timeout: 10000 });

		// Extract post ID for cleanup
		createdCourseId = await getPostIdFromUrl(page);

		// Navigate to frontend view
		const viewPostLink = page.locator('.components-snackbar a', {
			hasText: 'View Course',
		});
		await viewPostLink.click();

		// Verify course displays correctly on frontend
		await expect(
			page.locator('h1'),
			'Course title should be visible on frontend'
		).toContainText(courseData.title);
	});

	/**
	 * Test: Course Appears in Course List
	 *
	 * Verifies that newly created courses appear in the admin course list.
	 */
	test('should display course in admin course list', async ({ page }) => {
		// Create a test course first
		const courseData: CourseTestData = {
			title: `List Test Course ${Date.now()}`,
			content: 'Course for testing list display.',
		};

		// Navigate to Add New Course
		await page.goto('/wp-admin/post-new.php?post_type=courses');

		// Close welcome guide if present
		const welcomeGuide = page.locator(
			'button[aria-label="Close welcome guide"]'
		);
		if (await welcomeGuide.isVisible()) {
			await welcomeGuide.click();
		}

		// Create course
		await page.fill('#post-title-0', courseData.title);
		await page.click('.block-editor-default-block-appender__content');
		await page.keyboard.type(courseData.content);

		// Publish
		await page.click('.editor-post-publish-panel__toggle');
		await page.click('.editor-post-publish-button');

		// Wait for publish confirmation
		await expect(
			page.locator('.components-snackbar', { hasText: 'published' })
		).toBeVisible({ timeout: 10000 });

		// Extract post ID for cleanup
		createdCourseId = await getPostIdFromUrl(page);

		// Navigate to course list
		await page.goto('/wp-admin/edit.php?post_type=courses');

		// Verify course appears in list
		await expect(
			page.locator(`a.row-title`, { hasText: courseData.title }),
			'Course should appear in admin course list'
		).toBeVisible();
	});

	/**
	 * Test: Edit Course
	 *
	 * Tests the ability to edit an existing course and save changes.
	 */
	test('should edit and update existing course', async ({ page }) => {
		// Create a test course first
		const originalTitle = `Original Course ${Date.now()}`;
		const updatedTitle = `Updated Course ${Date.now()}`;

		// Navigate to Add New Course
		await page.goto('/wp-admin/post-new.php?post_type=courses');

		// Close welcome guide if present
		const welcomeGuide = page.locator(
			'button[aria-label="Close welcome guide"]'
		);
		if (await welcomeGuide.isVisible()) {
			await welcomeGuide.click();
		}

		// Create initial course
		await page.fill('#post-title-0', originalTitle);
		await page.click('.block-editor-default-block-appender__content');
		await page.keyboard.type('Original content.');

		// Publish
		await page.click('.editor-post-publish-panel__toggle');
		await page.click('.editor-post-publish-button');
		await expect(
			page.locator('.components-snackbar', { hasText: 'published' })
		).toBeVisible({ timeout: 10000 });

		// Extract post ID for cleanup
		createdCourseId = await getPostIdFromUrl(page);

		// Now edit the course - clear and update title
		await page.fill('#post-title-0', ''); // Clear existing title
		await page.fill('#post-title-0', updatedTitle);

		// Update the course
		await page.click('.editor-post-publish-button__button');

		// Verify update success
		await expect(
			page.locator('.components-snackbar', { hasText: 'updated' }),
			'Course should be updated successfully'
		).toBeVisible({ timeout: 10000 });

		// Verify title was updated
		await expect(page.locator('#post-title-0')).toHaveValue(
			updatedTitle
		);
	});

	/**
	 * Test: Course Custom Post Type Registration
	 *
	 * Verifies that the course post type is properly registered and accessible.
	 */
	test('should verify course post type is registered', async ({
		page,
	}) => {
		// Navigate directly to courses list
		await page.goto('/wp-admin/edit.php?post_type=courses');

		// Verify we're on the courses page (not redirected to 404)
		await expect(page).toHaveURL(/post_type=courses/);

		// Verify page heading
		await expect(
			page.locator('.wp-heading-inline, h1').first(),
			'Courses page should have proper heading'
		).toBeVisible();

		// Verify "Add New" button exists
		const addNewButton = page.locator('a.page-title-action', {
			hasText: 'Add New',
		});
		await expect(
			addNewButton,
			'Add New button should be visible'
		).toBeVisible();

		// Click Add New to verify we can access the editor
		await addNewButton.click();

		// Verify we're on the new course page
		await expect(page).toHaveURL(/post-new\.php\?post_type=courses/);

		// Verify editor is loaded
		await expect(
			page.locator('.block-editor'),
			'Block editor should be loaded for course post type'
		).toBeVisible({ timeout: 10000 });
	});
});
