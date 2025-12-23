import { Page, Locator, expect } from '@playwright/test';

export class CourseEditorPage {
	readonly page: Page;
	readonly titleInput: Locator;
	readonly contentArea: Locator;
	readonly publishPanelToggle: Locator;
	readonly publishButton: Locator;
	readonly snackbar: Locator;

	constructor( page: Page ) {
		this.page = page;
		// WP 6.x+ usually labels the title area "Add title"
		this.titleInput = page.getByRole( 'textbox', { name: 'Add title' } );
		this.contentArea = page.getByRole( 'document', {
			name: 'Block: Paragraph',
		} ); // Generic block appender start

		// More robust selectors for publishing flow
		this.publishPanelToggle = page.getByRole( 'button', {
			name: 'Publish',
			exact: true,
		} );
		this.publishButton = page.getByRole( 'button', {
			name: 'Publish',
			exact: true,
		} );
		this.snackbar = page.locator( '.components-snackbar' );
	}

	async gotoAddNew() {
		// Default CPT slug is 'services' as per class-sanitization.php
		await this.page.goto( '/wp-admin/post-new.php?post_type=services' );
		await this.handleWelcomeGuide();
	}

	async handleWelcomeGuide() {
		// Wait briefly for the guide to potentially appear
		try {
			const welcomeModal = this.page.locator(
				'.edit-post-welcome-guide'
			);
			if ( await welcomeModal.isVisible( { timeout: 2000 } ) ) {
				await this.page.getByLabel( 'Close welcome guide' ).click();
			}
		} catch ( e ) {
			// Provide a graceful fallback or ignore if not present
		}
	}

	async fillTitle( title: string ) {
		// Ensure the editor is ready - wait for *any* editor element
		try {
			// Wait for the main editor canvas or header to be ready
			await this.page.waitForSelector( '.interface-interface-skeleton', {
				timeout: 10000,
			} );
		} catch ( e ) {
			// Ignore
		}

		// 1. Try accessibility label (Standard Gutenberg)
		const labeledInput = this.page.getByRole( 'textbox', {
			name: 'Add title',
		} );
		if ( await labeledInput.isVisible( { timeout: 3000 } ) ) {
			await labeledInput.fill( title );
			return;
		}

		// 2. Try the post title class (Standard WP)
		const titleBlock = this.page.locator( '.editor-post-title__input' );
		if ( await titleBlock.isVisible( { timeout: 2000 } ) ) {
			await titleBlock.fill( title );
			return;
		}

		// 3. Try the block-based title (Newer WP)
		const wpBlockTitle = this.page.locator( '.wp-block-post-title' );
		if ( await wpBlockTitle.isVisible( { timeout: 2000 } ) ) {
			await wpBlockTitle.click();
			await this.page.keyboard.type( title );
			return;
		}

		// 4. Fallback: Keyboard "Blind" Typing
		// Assume focus starts near top or we can force it
		try {
			await this.page.keyboard.press( 'Control+Home' ); // Top of doc
			await this.page.waitForTimeout( 500 );
			await this.page.keyboard.press( 'Tab' ); // Often moves to title
			await this.page.waitForTimeout( 200 );
			// Type title blindly as last resort
			await this.page.keyboard.type( title );
		} catch ( e ) {
			console.log( 'Failed to fill title via fallbacks' );
		}
	}

	async addContent( content: string ) {
		// Just in case, wait a beat for the title interactions to settle
		await this.page.waitForTimeout( 500 );

		// Strategy 1: The default paragraph block appender (Empty state)
		const appender = this.page.locator(
			'.block-editor-default-block-appender__content'
		);
		if ( await appender.isVisible( { timeout: 2000 } ) ) {
			await appender.click();
			await this.page.keyboard.type( content );
			return;
		}

		// Strategy 2: Check if there's already a paragraph block we can type into
		const paragraphBlock = this.page
			.locator(
				'p[role="document"][aria-label="Block: Paragraph"], .block-editor-rich-text__editable'
			)
			.first();
		if ( await paragraphBlock.isVisible( { timeout: 2000 } ) ) {
			await paragraphBlock.click();
			await this.page.keyboard.type( content );
			return;
		}

		// Strategy 3: Keyboard default - just press Enter to create a new block
		try {
			await this.page.keyboard.press( 'Enter' );
			await this.page.waitForTimeout( 200 );
			await this.page.keyboard.type( content );
		} catch ( e ) {
			console.log( 'Failed to add content via keyboard fallback' );
		}
	}

	async publish() {
		// Wait for the publish button to be actionable
		await this.publishPanelToggle.waitFor( { state: 'visible' } );
		await this.publishPanelToggle.click();

		// Sometimes the panel opens and we need to click "Publish" again in the side panel
		// Wait for the side panel publish button to be visible
		const sidePanelPublish = this.page
			.locator( '.editor-post-publish-panel__header' )
			.getByRole( 'button', { name: 'Publish' } );

		// Use a short timeout to check for the side panel button, as it might handle single-click
		if ( await sidePanelPublish.isVisible( { timeout: 3000 } ) ) {
			await sidePanelPublish.click();

			// Wait for the secondary confirm if needed (rare config)
			const confirmRegion = this.page.getByRole( 'region', {
				name: 'Editor publish',
			} );
			const confirmButton = confirmRegion.getByRole( 'button', {
				name: 'Publish',
			} );
			if ( await confirmButton.isVisible( { timeout: 2000 } ) ) {
				await confirmButton.click();
			}
		}
	}

	async verifyPublished() {
		await expect( this.snackbar ).toContainText( 'published' ); // Flexible match
		await expect( this.snackbar ).toBeVisible();
	}

	async viewPublishedCourse() {
		const viewPostLink = this.snackbar.getByRole( 'link', {
			name: /View (Course|Post)/i,
		} );
		await viewPostLink.click();
	}
}
