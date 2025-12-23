<?php
/**
 * WhatsApp Styles
 *
 * Handles enqueuing of WhatsApp styles and scripts.
 *
 * @package Dynamic_Online_Services
 * @subpackage Styles
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Styles;

use TechmireSolutions\DynamicOnlineServices\Helpers\Options;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * WhatsApp Styles class.
 */
class WhatsappStyles
{
	/**
	 * Initialize hooks.
	 *
	 * @since 1.2.0
	 */
	public static function init(): void
	{
		add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue'));
	}

	/**
	 * Enqueue WhatsApp assets.
	 *
	 * @since 1.2.0
	 */
	public static function enqueue(): void
	{
		$options = Options::get();

		// 1. Check Global Enable
		if (!Options::get_option($options, 'whatsapp_enabled', false)) {
			return;
		}

		// 2. Check Page Visibility
		$visibility = Options::get_option($options, 'whatsapp_visibility', 'all');
		if ('home' === $visibility && !is_front_page()) {
			return;
		}

		// 3. Check Schedule & Offline Behavior
		// We duplicate is_open logic here or just load assets always if enabled?
		// Better to check is_open to save bytes if offline and "hide" behavior is set.
		$is_open = self::is_open($options);
		$offline_behavior = Options::get_option($options, 'whatsapp_offline_behavior', 'hide');

		if (!$is_open && 'hide' === $offline_behavior) {
			return;
		}

		// 4. Device Visibility
		$show_desktop = Options::get_option($options, 'whatsapp_show_desktop', true);
		$show_mobile  = Options::get_option($options, 'whatsapp_show_mobile', true);
		if (!$show_desktop && !$show_mobile) {
			return;
		}

		// Enqueue CSS
		wp_enqueue_style(
			'sos-whatsapp-style',
			DYNOS_PLUGIN_URL . 'assets/css/whatsapp.css',
			array(),
			DYNOS_VERSION
		);

		// Enqueue JS
		wp_enqueue_script(
			'sos-whatsapp-script',
			DYNOS_PLUGIN_URL . 'assets/js/whatsapp.js',
			array(),
			DYNOS_VERSION,
			true // In footer
		);
	}

	/**
	 * Check if the chat should be displayed based on schedule.
	 *
	 * @param array $options Plugin options.
	 * @return bool
	 */
	public static function is_open(array $options): bool
	{
		$availability = Options::get_option($options, 'whatsapp_availability', false);

		// If scheduling is not enabled, it's always available.
		if (!$availability) {
			return true;
		}

		$timezone_string = Options::get_option($options, 'whatsapp_timezone', 'UTC');
		try {
			$timezone = new \DateTimeZone($timezone_string);
		} catch (\Exception $e) {
			$timezone = new \DateTimeZone('UTC');
		}

		$current_time = new \DateTime('now', $timezone);
		$now          = $current_time->format('H:i');

		$start = Options::get_option($options, 'whatsapp_schedule_start', '09:00');
		$end   = Options::get_option($options, 'whatsapp_schedule_end', '17:00');

		return $now >= $start && $now <= $end;
	}
}
