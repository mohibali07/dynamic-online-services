<?php
/**
 * Taxonomy Fields Saver
 *
 * Handles saving of custom taxonomy field data.
 *
 * @package Dynamic_Online_Services
 * @subpackage Taxonomy_Fields
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Save custom fields data when a term is created or edited.
 *
 * @since 1.1.0
 * @param int $term_id Term ID.
 * @return void
 */
function dynos_save_service_category_custom_fields($term_id): void
{
	// Verify nonce
	if (
		!isset($_POST['dynos_service_category_fields_nonce']) ||
		!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['dynos_service_category_fields_nonce'])), 'dynos_service_category_fields')
	) {
		return;
	}

	// Check user permissions
	if (!current_user_can('edit_term', $term_id)) {
		return;
	}

	// Validate term ID
	$term_id = absint($term_id);
	if (0 === $term_id) {
		return;
	}

	// Save thumbnail
	if (isset($_POST['service_cat_thumbnail'])) {
		$thumbnail_id_raw = isset($_POST['service_cat_thumbnail']) ? sanitize_text_field(wp_unslash($_POST['service_cat_thumbnail'])) : '';
		$thumbnail_id = absint($thumbnail_id_raw);

		// If ID is 0 or empty, delete the meta (allows clearing thumbnail)
		if (0 === $thumbnail_id || empty($thumbnail_id_raw)) {
			delete_term_meta($term_id, 'service_cat_thumbnail');
		} else {
			// Validate attachment using centralized helper function
			$validated_thumbnail_id = dynos_validate_attachment_id($thumbnail_id);
			if (false !== $validated_thumbnail_id) {
				// Allow filtering before saving
				$validated_thumbnail_id = apply_filters('dynos_before_save_category_thumbnail', $validated_thumbnail_id, $term_id);
				update_term_meta($term_id, 'service_cat_thumbnail', $validated_thumbnail_id);

				// Fire action after saving
				do_action('dynos_after_save_category_thumbnail', $term_id, $validated_thumbnail_id);
			}
		}
	}
}

/**
 * Initialize taxonomy fields saver hooks with dynamic taxonomy slug.
 *
 * @since 1.2.0
 */
function dynos_init_service_category_fields_saver(): void
{
	$settings = \TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization::sanitize_cpt_settings();
	$taxonomy_slug = isset($settings['taxonomy_slug']) ? $settings['taxonomy_slug'] : 'services_category';

	add_action('created_' . $taxonomy_slug, 'dynos_save_service_category_custom_fields', 10, 1);
	add_action('edited_' . $taxonomy_slug, 'dynos_save_service_category_custom_fields', 10, 1);
}
add_action('init', 'dynos_init_service_category_fields_saver');
