/**
 * Taxonomy Media Uploader JavaScript
 *
 * Integrates WordPress Media Library with taxonomy term forms for thumbnail management.
 * Provides upload, preview, and removal functionality for category images.
 *
 * @package    Dynamic_Online_Services
 * @subpackage Assets/JavaScript
 * @since      1.0.0
 */

(function ($) {
	'use strict';

	// Constants for selectors and configuration
	const SELECTOR_THUMBNAIL_INPUT = '#service-cat-thumbnail';
	const SELECTOR_PREVIEW_CONTAINER = '#service-cat-thumbnail-preview';
	const SELECTOR_UPLOAD_BUTTON = '#upload-thumbnail-button';
	const SELECTOR_REMOVE_BUTTON = '#remove-thumbnail-button';
	const PREVIEW_MAX_WIDTH = '200px';
	const PREVIEW_MAX_HEIGHT = '200px';

	/**
	 * Regular expression for validating image URLs.
	 *
	 * Allows: http://, https://, //, data:image/
	 *
	 * @type {RegExp}
	 * @since 1.0.0
	 */
	const URL_VALIDATION_REGEX = /^(https?:\/\/|\/\/|data:image\/)/i;

	$(document).ready(function () {
		try {
			// Defensive check: ensure WordPress media library is available
			if (
				typeof wp === 'undefined' ||
				typeof wp.media === 'undefined'
			) {
				if (typeof window.sosConsoleLog === 'function') {
					window.sosConsoleLog(
						'error',
						'SOS Taxonomy Media Uploader: WordPress media library not available'
					);
				}
				// Show user-friendly error message
				if (typeof window.sosShowAdminError === 'function') {
					window.sosShowAdminError(
						'SOS Taxonomy Media Uploader: WordPress media library is not available. Please refresh the page.'
					);
				} else if (typeof window.sosConsoleLog === 'function') {
					window.sosConsoleLog(
						'error',
						'SOS Taxonomy Media Uploader: WordPress media library is not available.'
					);
				}
				return;
			}

			// Defensive check: ensure localized object exists
			if (typeof dynosTaxonomyMedia === 'undefined') {
				if (typeof window.sosConsoleLog === 'function') {
					window.sosConsoleLog(
						'warn',
						'DYNOS Taxonomy Media Uploader: dynosTaxonomyMedia object not found - using defaults'
					);
				}
				// Provide fallback values
				window.dynosTaxonomyMedia = {
					title: 'Select Thumbnail',
					button: 'Use this image',
				};
			}

			/**
			 * WordPress media uploader instance.
			 *
			 * @type {Object|null}
			 */
			let mediaUploader = null;

			/**
			 * Validate image URL format.
			 *
			 * @since 1.0.0
			 *
			 * @param {string} url The URL to validate.
			 *
			 * @return {boolean} True if valid, false otherwise.
			 */
			function isValidImageUrl(url) {
				if (!url || typeof url !== 'string') {
					return false;
				}
				return URL_VALIDATION_REGEX.test(url);
			}

			/**
			 * Update the thumbnail preview with a new image.
			 *
			 * @since 1.0.0
			 *
			 * @param {Object} attachment WordPress media attachment object.
			 * @param {string} attachment.url Image URL.
			 * @param {string} attachment.alt Image alt text.
			 *
			 * @return {void}
			 */
			function updateThumbnailPreview(attachment) {
				const imageUrl = attachment.url || '';
				const imageAlt = attachment.alt || '';

				// Validate URL format
				if (!isValidImageUrl(imageUrl)) {
					if (typeof window.sosConsoleLog === 'function') {
						window.sosConsoleLog(
							'error',
							'SOS Taxonomy Media Uploader: Invalid image URL format',
							imageUrl
						);
					}
					if (typeof window.sosShowAdminError === 'function') {
						window.sosShowAdminError(
							'SOS Taxonomy Media Uploader: Invalid image URL. Please select a different image.'
						);
					}
					return;
				}

				const $preview = $(SELECTOR_PREVIEW_CONTAINER);
				const $previewImg = $preview.find('img');

				if ($previewImg.length > 0) {
					// Update existing image
					$previewImg
						.attr('src', imageUrl)
						.attr('alt', imageAlt);
					$preview.show();
				} else {
					// Create new image element
					const $newImg = $('<img>', {
						src: imageUrl,
						alt: imageAlt,
						css: {
							'max-width': PREVIEW_MAX_WIDTH,
							'max-height': PREVIEW_MAX_HEIGHT,
							display: 'block',
						},
					});
					$preview.empty().append($newImg).show();
				}

				// Show remove button
				$(SELECTOR_REMOVE_BUTTON).show();
			}

			/**
			 * Handle media selection from WordPress Media Library.
			 *
			 * @since 1.0.0
			 *
			 * @return {void}
			 */
			function handleMediaSelection() {
				const attachment = mediaUploader
					.state()
					.get('selection')
					.first()
					.toJSON();

				const attachmentId = parseInt(attachment.id, 10);

				// Validate attachment ID
				if (!attachmentId || isNaN(attachmentId)) {
					if (typeof window.sosConsoleLog === 'function') {
						window.sosConsoleLog(
							'error',
							'SOS Taxonomy Media Uploader: Invalid attachment ID',
							attachment
						);
					}
					if (typeof window.sosShowAdminError === 'function') {
						window.sosShowAdminError(
							'SOS Taxonomy Media Uploader: Invalid image selected. Please try selecting a different image.'
						);
					}
					return;
				}

				// Update hidden input field with attachment ID
				$(SELECTOR_THUMBNAIL_INPUT).val(attachmentId);

				// Update preview
				updateThumbnailPreview(attachment);

				// Log success
				if (typeof window.sosConsoleLog === 'function') {
					window.sosConsoleLog(
						'log',
						'SOS Taxonomy Media Uploader: Thumbnail set',
						attachmentId
					);
				}
			}

			/**
			 * Handle upload button click event.
			 *
			 * Opens WordPress Media Library or creates new instance if needed.
			 * Works for both add and edit taxonomy forms.
			 *
			 * @since 1.0.0
			 *
			 * @param {Event} e Click event.
			 *
			 * @return {void}
			 */
			$(document).on(
				'click',
				SELECTOR_UPLOAD_BUTTON,
				function (e) {
					e.preventDefault();

					// Reopen existing uploader if available
					if (mediaUploader) {
						mediaUploader.open();
						return;
					}

					// Create new media uploader instance
					mediaUploader = wp.media({
						title: dynosTaxonomyMedia.title,
						button: {
							text: dynosTaxonomyMedia.button,
						},
						multiple: false,
						library: {
							type: 'image', // Only show images
						},
					});

					// Bind selection handler
					mediaUploader.on('select', handleMediaSelection);

					// Open media library
					mediaUploader.open();
				}
			);

			/**
			 * Handle remove button click event.
			 *
			 * Clears the thumbnail input and removes the preview image.
			 * Works for both add and edit taxonomy forms.
			 *
			 * @since 1.0.0
			 *
			 * @param {Event} e Click event.
			 *
			 * @return {void}
			 */
			$(document).on(
				'click',
				SELECTOR_REMOVE_BUTTON,
				function (e) {
					e.preventDefault();
					try {
						// Clear thumbnail input field
						const $thumbnailInput = $(SELECTOR_THUMBNAIL_INPUT);
						if ($thumbnailInput.length === 0) {
							if (typeof window.sosConsoleLog === 'function') {
								window.sosConsoleLog(
									'warn',
									'SOS Taxonomy Media Uploader: Thumbnail input not found'
								);
							}
							return;
						}

						$thumbnailInput.val('');

						// Remove preview image
						const $preview = $(SELECTOR_PREVIEW_CONTAINER);
						if ($preview.length > 0) {
							$preview.find('img').remove();
							$preview.hide();
						}

						// Hide remove button
						$(this).hide();

						// Log successful removal
						if (typeof window.sosConsoleLog === 'function') {
							window.sosConsoleLog(
								'log',
								'SOS Taxonomy Media Uploader: Thumbnail removed'
							);
						}
					} catch (error) {
						// Log error
						if (typeof window.sosConsoleLog === 'function') {
							window.sosConsoleLog(
								'error',
								'SOS Taxonomy Media Uploader: Error removing thumbnail',
								error
							);
						}
						// Show error message
						if (typeof window.sosShowAdminError === 'function') {
							window.sosShowAdminError(
								'SOS Taxonomy Media Uploader: Error removing thumbnail. Please try again.'
							);
						}
					}
				}
			);
		} catch (error) {
			// Log error to console in development
			if (typeof window.sosConsoleLog === 'function') {
				window.sosConsoleLog(
					'error',
					'SOS Taxonomy Media Uploader Error:',
					error
				);
			}
			// Show user-friendly error message
			// Use shared error handling function from admin-common.js
			if (typeof window.sosShowAdminError === 'function') {
				window.sosShowAdminError(
					'SOS Taxonomy Media Uploader: An error occurred. Please try refreshing the page. If the problem persists, contact support.'
				);
			} else if (typeof window.sosConsoleLog === 'function') {
				window.sosConsoleLog(
					'error',
					'SOS Taxonomy Media Uploader: An error occurred. Please try refreshing the page.'
				);
			}
		}
	});
})(jQuery);
