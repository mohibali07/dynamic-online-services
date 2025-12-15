<?php
/**
 * Post Type Sanitization
 *
 * Handles sanitization and validation of post type and taxonomy settings.
 *
 * @package Dynamic_Online_Services
 * @subpackage PostTypes
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\PostTypes;

use TechmireSolutions\DynamicOnlineServices\Helpers\Options;
use TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization as HelperSanitization;
use TechmireSolutions\DynamicOnlineServices\Helpers\AdminNotices;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Sanitization class.
 */
class Sanitization
{
	/**
	 * Sanitize and validate post type and taxonomy settings.
	 *
	 * @since 1.1.0
	 * @return array Array with sanitized 'service_slug', 'taxonomy_slug', 'menu_position', and 'menu_icon'.
	 */
	public static function sanitize_cpt_settings(): array
	{
		$options = Options::get();
		$service_slug = Options::get_option($options, 'service_post_type_slug', 'services');
		$taxonomy_slug = Options::get_option($options, 'service_taxonomy_slug', 'service-category');
		$menu_position = Options::get_option($options, 'service_menu_position', '');
		$menu_icon = Options::get_option($options, 'service_menu_icon', 'dashicons-admin-customizer');

		// Custom Labels
		$cpt_singular = Options::get_option($options, 'service_cpt_singular_name', 'Service');
		$cpt_plural = Options::get_option($options, 'service_cpt_plural_name', 'Services');
		$tax_singular = Options::get_option($options, 'service_tax_singular_name', 'Service Category');
		$tax_plural = Options::get_option($options, 'service_tax_plural_name', 'Service Categories');

		// Sanitize slugs using centralized validation function
		$service_slug = HelperSanitization::validate_slug($service_slug, 'services');
		$taxonomy_slug = HelperSanitization::validate_slug($taxonomy_slug, 'service-category');

		// Validate menu position
		$menu_position = self::validate_menu_position($menu_position);

		// Validate menu icon
		$menu_icon = self::validate_menu_icon($menu_icon);

		return array(
			'service_slug' => $service_slug,
			'taxonomy_slug' => $taxonomy_slug,
			'menu_position' => $menu_position,
			'menu_icon' => $menu_icon,
			'cpt_singular' => $cpt_singular,
			'cpt_plural' => $cpt_plural,
			'tax_singular' => $tax_singular,
			'tax_plural' => $tax_plural,
		);
	}

	/**
	 * Validate menu position value.
	 *
	 * @since 1.1.0
	 * @param mixed $menu_position Menu position value.
	 * @return int|null Validated menu position or null for default.
	 */
	public static function validate_menu_position($menu_position): ?int
	{
		$menu_position = !empty($menu_position) ? absint($menu_position) : null;

		if (null !== $menu_position && ($menu_position < 0 || $menu_position > 100)) {
			// Use centralized validation warning function
			$warning_message = sprintf(
				/* translators: %s: Invalid menu position value */
				esc_html__('Dynamic Online Services: Invalid menu position value (%s). Menu position must be between 0 and 100, or left empty. Using default position.', 'dynamic-online-services'),
				esc_html((string)$menu_position)
			);
			AdminNotices::add_validation_warning($warning_message, 'Invalid menu position ' . $menu_position . '. Using default.');
			$menu_position = null;
		}

		return $menu_position;
	}

	/**
	 * Validate menu icon format.
	 *
	 * @since 1.1.0
	 * @param string $menu_icon Menu icon class name.
	 * @return string Validated menu icon class name.
	 */
	public static function validate_menu_icon(string $menu_icon): string
	{
		$menu_icon = sanitize_text_field($menu_icon);
		if (empty($menu_icon)) {
			return 'dashicons-admin-customizer';
		}

		// Validate dashicon format (basic check)
		if (!preg_match('/^dashicons-[a-z0-9-]+$/i', $menu_icon)) {
			// Use centralized validation warning function
			$warning_message = sprintf(
				/* translators: %s: Invalid menu icon value */
				esc_html__('Dynamic Online Services: Invalid menu icon format (%s). Icon must be a valid Dashicon class name (e.g., dashicons-admin-customizer). Using default icon.', 'dynamic-online-services'),
				esc_html($menu_icon)
			);
			AdminNotices::add_validation_warning($warning_message, 'Invalid menu icon format: ' . $menu_icon . '. Using default.');
			return 'dashicons-admin-customizer';
		}

		return $menu_icon;
	}
}
