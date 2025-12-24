/**
 * Taxonomy Media Uploader JavaScript
 *
 * @param      $
 * @package
 * @subpackage Assets
 */

( function ( $ ) {
	'use strict';

	$( document ).ready( function () {
		try {
			// Defensive check: ensure WordPress media library is available
			if (
				typeof wp === 'undefined' ||
				typeof wp.media === 'undefined'
			) {
				if ( typeof window.sosConsoleLog === 'function' ) {
					window.sosConsoleLog(
						'error',
						'SOS Taxonomy Media Uploader: WordPress media library not available'
					);
				}
				// Show user-friendly error message
				if ( typeof window.sosShowAdminError === 'function' ) {
					window.sosShowAdminError(
						'SOS Taxonomy Media Uploader: WordPress media library is not available. Please refresh the page.'
					);
				} else if ( typeof window.sosConsoleLog === 'function' ) {
					window.sosConsoleLog(
						'error',
						'SOS Taxonomy Media Uploader: WordPress media library is not available.'
					);
				}
				return;
			}

			// Defensive check: ensure localized object exists
			if ( typeof sosTaxonomyMedia === 'undefined' ) {
				if ( typeof window.sosConsoleLog === 'function' ) {
					window.sosConsoleLog(
						'warn',
						'SOS Taxonomy Media Uploader: sosTaxonomyMedia object not found'
					);
				}
			}

			let mediaUploader;

			// Handle upload button click (works for both add and edit forms)
			$( document ).on(
				'click',
				'#upload-thumbnail-button',
				function ( e ) {
					e.preventDefault();

					if ( mediaUploader ) {
						mediaUploader.open();
						return;
					}

					mediaUploader = wp.media( {
						title: sosTaxonomyMedia.title,
						button: {
							text: sosTaxonomyMedia.button,
						},
						multiple: false,
					} );

					mediaUploader.on( 'select', function () {
						const attachment = mediaUploader
							.state()
							.get( 'selection' )
							.first()
							.toJSON();
						const attachmentId = parseInt( attachment.id, 10 );

						// Validate attachment ID
						if ( ! attachmentId || isNaN( attachmentId ) ) {
							if ( typeof window.sosConsoleLog === 'function' ) {
								window.sosConsoleLog(
									'error',
									'SOS Taxonomy Media Uploader: Invalid attachment ID'
								);
							}
							if (
								typeof window.sosShowAdminError === 'function'
							) {
								window.sosShowAdminError(
									'SOS Taxonomy Media Uploader: Invalid image selected. Please try selecting a different image.'
								);
							} else if (
								typeof window.sosConsoleLog === 'function'
							) {
								window.sosConsoleLog(
									'error',
									'SOS Taxonomy Media Uploader: Invalid image selected.'
								);
							}
							return;
						}

						$( '#service-cat-thumbnail' ).val( attachmentId );

						const $preview = $( '#service-cat-thumbnail-preview' );
						// Check if preview container has an img element
						const $previewImg = $preview.find( 'img' );
						if ( $previewImg.length > 0 ) {
							// Update existing image - escape URL to prevent XSS
							$previewImg
								.attr( 'src', attachment.url || '' )
								.attr( 'alt', attachment.alt || '' );
							$preview.show();
						} else {
							// Create new image element using safer jQuery methods
							// WordPress media library already sanitizes attachment.url, but we escape to be safe
							const imageUrl = attachment.url || '';
							const imageAlt = attachment.alt || '';

							// Basic URL validation to prevent XSS
							if (
								imageUrl &&
								! imageUrl.match( /^https?:\/\//i ) &&
								! imageUrl.match( /^\/\//i ) &&
								! imageUrl.match( /^data:image\//i )
							) {
								if (
									typeof window.sosConsoleLog === 'function'
								) {
									window.sosConsoleLog(
										'error',
										'SOS Taxonomy Media Uploader: Invalid image URL format'
									);
								}
								if (
									typeof window.sosShowAdminError ===
									'function'
								) {
									window.sosShowAdminError(
										'SOS Taxonomy Media Uploader: Invalid image URL. Please select a different image.'
									);
								} else if (
									typeof window.sosConsoleLog === 'function'
								) {
									window.sosConsoleLog(
										'error',
										'SOS Taxonomy Media Uploader: Invalid image URL.'
									);
								}
								return;
							}

							const $newImg = $( '<img>', {
								src: imageUrl,
								alt: imageAlt,
								css: {
									'max-width': '200px',
									'max-height': '200px',
									display: 'block',
								},
							} );
							$preview.empty().append( $newImg ).show();
						}

						$( '#remove-thumbnail-button' ).show();
					} );

					mediaUploader.open();
				}
			);

			// Handle remove button click (works for both add and edit forms)
			$( document ).on(
				'click',
				'#remove-thumbnail-button',
				function ( e ) {
					e.preventDefault();
					try {
						const $thumbnailInput = $( '#service-cat-thumbnail' );
						if ( $thumbnailInput.length === 0 ) {
							return;
						}
						$thumbnailInput.val( '' );
						const $preview = $( '#service-cat-thumbnail-preview' );
						if ( $preview.length > 0 ) {
							$preview.find( 'img' ).remove();
							$preview.hide();
						}
						$( this ).hide();
					} catch ( error ) {
						if ( typeof window.sosConsoleLog === 'function' ) {
							window.sosConsoleLog(
								'error',
								'SOS Taxonomy Media Uploader: Error removing thumbnail',
								error
							);
						}
						if ( typeof window.sosShowAdminError === 'function' ) {
							window.sosShowAdminError(
								'SOS Taxonomy Media Uploader: Error removing thumbnail. Please try again.'
							);
						} else if (
							typeof window.sosConsoleLog === 'function'
						) {
							window.sosConsoleLog(
								'error',
								'SOS Taxonomy Media Uploader: Error removing thumbnail.'
							);
						}
					}
				}
			);
		} catch ( error ) {
			// Log error to console in development
			if ( typeof window.sosConsoleLog === 'function' ) {
				window.sosConsoleLog(
					'error',
					'SOS Taxonomy Media Uploader Error:',
					error
				);
			}
			// Show user-friendly error message
			// Use shared error handling function from admin-common.js
			if ( typeof window.sosShowAdminError === 'function' ) {
				window.sosShowAdminError(
					'SOS Taxonomy Media Uploader: An error occurred. Please try refreshing the page. If the problem persists, contact support.'
				);
			} else if ( typeof window.sosConsoleLog === 'function' ) {
				window.sosConsoleLog(
					'error',
					'SOS Taxonomy Media Uploader: An error occurred. Please try refreshing the page.'
				);
			}
		}
	} );
} )( jQuery );
