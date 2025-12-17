<?php
/**
 * Settings Menu Provider Class
 *
 * Responsible for registering WordPress admin menu items.
 *
 * @package Dynamic_Online_Services
 * @subpackage Admin
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Admin;

use TechmireSolutions\DynamicOnlineServices\Settings\PageRenderer;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * SettingsMenuProvider class.
 *
 * Single responsibility: Register admin menu items.
 *
 * @since 1.1.3
 */
class SettingsMenuProvider
{

	/**
	 * Register admin menu.
	 *
	 * @since 1.1.3
	 * @return void
	 */
	public static function register(): void
	{
		add_options_page(
			__('Dynamic Online Services Settings', 'dynamic-online-services'),
			__('Dynamic Services', 'dynamic-online-services'),
			'manage_options',
			'dynamic-online-services',
			[PageRenderer::class, 'render']
		);
	}
}
