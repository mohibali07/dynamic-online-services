import { test, expect } from '@playwright/test';
import { LoginPage } from './pages/LoginPage';
import { CourseEditorPage } from './pages/CourseEditorPage';
import AxeBuilder from '@axe-core/playwright';

test.describe( 'Course Management', () => {
	let loginPage: LoginPage;
	let courseEditorPage: CourseEditorPage;

	test.beforeEach( async ( { page } ) => {
		loginPage = new LoginPage( page );
		courseEditorPage = new CourseEditorPage( page );

		// Login
		await loginPage.goto();
		await loginPage.login();
	} );

	test( 'should create and publish a new course and pass accessibility checks', async ( {
		page,
	} ) => {
		// Navigate to Add New Course
		await courseEditorPage.gotoAddNew();

		// Accessibility Check: Editor Page
		const accessibilityScanResults = await new AxeBuilder( { page } )
			.withTags( [ 'wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa' ] )
			.exclude( '#wpadminbar' ) // Exclude admin bar if it causes known issues
			.exclude( '.score-text' ) // Exclude potential SEO/Readability score elements in editor
			.analyze();

		expect( accessibilityScanResults.violations ).toEqual( [] );

		// Enter title
		const title = `Test Course ${ Date.now() }`;
		await courseEditorPage.fillTitle( title );

		// Enter content
		await courseEditorPage.addContent(
			'This is a test course description.'
		);

		// Publish
		await courseEditorPage.publish();

		// Verify published
		await courseEditorPage.verifyPublished();

		// View post
		await courseEditorPage.viewPublishedCourse();

		// Verify frontend
		await expect( page.locator( 'h1' ) ).toContainText( title );

		// Accessibility Check: Frontend Single Course
		const frontendScanResults = await new AxeBuilder( { page } )
			.withTags( [ 'wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa' ] )
			.exclude( '#wpadminbar' )
			.analyze();

		expect( frontendScanResults.violations ).toEqual( [] );
	} );
} );
