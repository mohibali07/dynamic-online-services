<?php
/**
 * Image Helper Class
 *
 * Handles image-related functionality including placeholder images.
 *
 * @package Dynamic_Online_Services
 * @subpackage Helpers
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Helpers;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Images helper class.
 */
class Images
{
	/**
	 * Get default placeholder image URL.
	 *
	 * @since 1.1.0
	 * @param string $size Image size. Default 'full'. Currently not used but reserved for future implementation.
	 * @return string Placeholder image URL. Returns a data URI SVG if no custom placeholder is found.
	 */
	public static function get_placeholder_url(string $size = 'full'): string
	{
		// Allow filtering the placeholder URL
		$placeholder_url = apply_filters('dynos_placeholder_image_url', '', $size);

		if (!empty($placeholder_url)) {
			return esc_url($placeholder_url);
		}

		// Try to use a local placeholder if it exists
		$local_placeholder = DYNOS_PLUGIN_URL . 'assets/images/placeholder.png';
		$local_path = DYNOS_PLUGIN_DIR . 'assets/images/placeholder.png';

		// Check if file exists and is readable
		if (file_exists($local_path) && is_readable($local_path)) {
			// Verify it's actually an image file
			// Use error suppression carefully here as getimagesize emits warning for non-images
			// We verified file exists and is readable, so warning is acceptable if file is corrupted
			// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			$image_info = @getimagesize($local_path);
			if (false !== $image_info) {
				return esc_url($local_placeholder);
			}
		}

		// Fallback to a simple data URI placeholder (SVG)
		// Using data URI avoids external dependencies and works immediately
		$no_image_text = __('No Image', 'dynamic-online-services');

		// Sanitize text to prevent XSS in SVG - strip tags and escape for XML context
		// SVG is XML, so we need XML-safe escaping
		$no_image_text = wp_strip_all_tags($no_image_text);
		// Escape for XML/SVG context (escapes <, >, &, ", ')
		$no_image_text = esc_html($no_image_text);

		// Whitelist approach: Only allow safe SVG elements and attributes
		// Define allowed SVG structure with whitelisted elements and attributes
		$allowed_svg_elements = ['svg', 'rect', 'text'];
		$allowed_svg_attributes = [
			'xmlns',
			'width',
			'height',
			'viewBox',
			'fill',
			'x',
			'y',
			'text-anchor',
			'dy',
			'font-family',
			'font-size',
		];

		// Build SVG using whitelisted structure only
		// This prevents injection of malicious SVG elements or attributes
		$svg_content = sprintf(
			'<svg xmlns="http://www.w3.org/2000/svg" width="400" height="232" viewBox="0 0 400 232"><rect width="400" height="232" fill="#e0e0e0"/><text x="50%%" y="50%%" text-anchor="middle" dy=".3em" fill="#999" font-family="sans-serif" font-size="16">%s</text></svg>',
			$no_image_text
		);

		// Validate SVG structure before encoding
		// Ensure we have a valid SVG structure with only whitelisted elements
		if (strpos($svg_content, '<svg') === false || strpos($svg_content, '</svg>') === false) {
			// Fallback to a minimal valid SVG if structure is invalid
			$svg_content = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="232" viewBox="0 0 400 232"><rect width="400" height="232" fill="#e0e0e0"/></svg>';
		}

		// Enhanced security validation: whitelist-based approach
		// Block all potentially dangerous patterns
		$dangerous_patterns = [
			'/<script/i',                    // Script tags
			'/<\/script>/i',                 // Closing script tags
			'/on\w+\s*=/i',                  // Event handlers (onclick, onload, etc.)
			'/javascript:/i',                 // JavaScript protocol
			'/data:text\/html/i',            // Data URI with HTML
			'/<iframe/i',                    // Iframe elements
			'/<object/i',                    // Object elements
			'/<embed/i',                     // Embed elements
			'/<link/i',                      // Link elements
			'/<style/i',                     // Style elements (could contain malicious CSS)
			'/expression\s*\(/i',            // CSS expressions
			'/@import/i',                    // CSS imports
		];

		foreach ($dangerous_patterns as $pattern) {
			if (preg_match($pattern, $svg_content)) {
				// If malicious content detected, use minimal safe SVG (no text)
				$svg_content = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="232" viewBox="0 0 400 232"><rect width="400" height="232" fill="#e0e0e0"/></svg>';
				break; // Exit loop once malicious content is found
			}
		}

		// Additional validation: ensure only whitelisted SVG elements are present
		// Extract all tags and verify they're in the whitelist
		preg_match_all('/<(\w+)/i', $svg_content, $matches);
		if (!empty($matches[1])) {
			foreach ($matches[1] as $tag) {
				$tag_lower = strtolower($tag);
				if (!in_array($tag_lower, $allowed_svg_elements, true)) {
					// Unknown element found, use safe fallback
					$svg_content = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="232" viewBox="0 0 400 232"><rect width="400" height="232" fill="#e0e0e0"/></svg>';
					break;
				}
			}
		}

		// Encode SVG content safely using base64
		// Base64 encoding ensures safe embedding in data URI
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Used for data URI generation, not obfuscation
		$encoded = base64_encode($svg_content);
		if (false === $encoded) {
			// Fallback if encoding fails (shouldn't happen, but defensive)
			return '';
		}

		// Return data URI format: data:[media-type][;base64],<data>
		return 'data:image/svg+xml;base64,' . $encoded;
	}

	/**
	 * Get service banner image with fallback to featured image.
	 *
	 * This function consolidates the duplicate image fallback logic used across
	 * the plugin. It first tries to get the ACF banner_bg_image field, and if
	 * that's not available, falls back to the post's featured image.
	 *
	 * @since 1.1.0
	 * @param int $post_id Post ID.
	 * @return array Image array with 'url' and optionally 'alt' keys, or empty array if no image found.
	 */
	public static function get_service_banner_image(int $post_id): array
	{
		$post_id = absint($post_id);
		if (0 === $post_id) {
			return [];
		}

		// Get ACF banner image field
		$image_array = Acf::get_field('banner_bg_image', $post_id, []);

		// Fallback: use featured image if ACF image is not available
		if (empty($image_array) || !is_array($image_array)) {
			$thumbnail_id = get_post_thumbnail_id($post_id);
			if ($thumbnail_id) {
				$image_url = wp_get_attachment_image_url($thumbnail_id, 'full');
				if ($image_url) {
					$image_array = ['url' => $image_url];
					// Try to get alt text from featured image
					$image_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
					if (!empty($image_alt)) {
						$image_array['alt'] = $image_alt;
					}
				}
			}
		}

		// Ensure we return a valid array structure
		if (is_array($image_array) && isset($image_array['url'])) {
			return $image_array;
		}

		return [];
	}

	/**
	 * Get service description with fallback to excerpt.
	 *
	 * This function consolidates the duplicate description fallback logic.
	 * It first tries to get the ACF banner_description field, and if that's
	 * not available, falls back to the post excerpt.
	 *
	 * @since 1.1.0
	 * @param int $post_id Post ID.
	 * @return string Description text or empty string.
	 */
	public static function get_service_description(int $post_id): string
	{
		$post_id = absint($post_id);
		if (0 === $post_id) {
			return '';
		}

		// Get ACF description field
		$description = Acf::get_field('banner_description', $post_id, '');

		// Fallback: use post excerpt if ACF description is not available
		if (empty($description)) {
			$description = get_the_excerpt($post_id);
		}

		return $description;
	}
}
