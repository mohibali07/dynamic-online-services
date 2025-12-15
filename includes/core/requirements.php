<?php
/**
 * Requirements Checker
 *
 * Handles checking of minimum PHP and WordPress version requirements.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Check if the minimum requirements are met.
 *
 * @since 1.1.0
 * @return bool True if requirements are met, false otherwise.
 */
function dynos_check_requirements(): bool
{
	// Get WordPress version without using global variable
	$wp_version = get_bloginfo('version');

	// Check PHP version
	if (version_compare(PHP_VERSION, DYNOS_MIN_PHP_VERSION, '<')) {
		add_action('admin_notices', 'dynos_php_version_notice');
		return false;
	}

	// Check WordPress version
	if (version_compare($wp_version, DYNOS_MIN_WP_VERSION, '<')) {
		add_action('admin_notices', 'dynos_wp_version_notice');
		return false;
	}

	return true;
}

/**
 * Display PHP version requirement notice.
 *
 * @since 1.1.0
 */
function dynos_php_version_notice(): void
{
	?>
	<div class="notice notice-error">
		<p>
			<?php
			printf(
				/* translators: 1: Current PHP version, 2: Required PHP version */
				esc_html__('Dynamic Online Services requires PHP version %2$s or higher. You are running PHP %1$s. Please upgrade PHP.', 'dynamic-online-services'),
				esc_html(PHP_VERSION),
				esc_html(DYNOS_MIN_PHP_VERSION)
			);
			?>
		</p>
	</div>
	<?php
}

/**
 * Display WordPress version requirement notice.
 *
 * @since 1.1.0
 */
function dynos_wp_version_notice(): void
{
	// Get WordPress version without using global variable
	$wp_version = get_bloginfo('version');
	?>
	<div class="notice notice-error">
		<p>
			<?php
			printf(
				/* translators: 1: Current WordPress version, 2: Required WordPress version */
				esc_html__('Dynamic Online Services requires WordPress version %2$s or higher. You are running WordPress %1$s. Please upgrade WordPress.', 'dynamic-online-services'),
				esc_html($wp_version),
				esc_html(DYNOS_MIN_WP_VERSION)
			);
			?>
		</p>
	</div>
	<?php
}

