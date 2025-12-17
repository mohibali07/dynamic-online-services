<?php
/**
 * Taxonomy Fields Saver Class
 *
 * Handles saving of custom taxonomy field data.
 *
 * @package Dynamic_Online_Services
 * @subpackage Taxonomies
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Taxonomies;

use TechmireSolutions\DynamicOnlineServices\Helpers\RateLimiter;
use TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization;
use TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization as CptSanitization;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class FieldsSaver
 */
class FieldsSaver
{

	/**
	 * Initialize hooks.
	 *
	 * @return void
	 */
	public static function init(): void
	{
		$settings = CptSanitization::sanitize_cpt_settings();
		$taxonomy_slug = isset($settings['taxonomy_slug']) ? $settings['taxonomy_slug'] : 'services_category';

		add_action('created_' . $taxonomy_slug, [self::class, 'save_fields'], 10, 1);
		add_action('edited_' . $taxonomy_slug, [self::class, 'save_fields'], 10, 1);
	}

	/**
	 * Save custom fields data when a term is created or edited.
	 *
	 * @param int $term_id Term ID.
	 * @return void
	 */
	public static function save_fields($term_id): void
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

		// Rate limiting: Prevent abuse (15 attempts per minute)
		$user_id = get_current_user_id();
		if (!RateLimiter::check('taxonomy_save', $user_id, 15, 60)) {
			if (defined('WP_DEBUG') && WP_DEBUG) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				error_log('DYNOS: Taxonomy save rate limit exceeded for user ' . $user_id);
			}
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
				// Validate attachment using Helper
				$validated_thumbnail_id = Sanitization::validate_attachment_id($thumbnail_id);
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
}
