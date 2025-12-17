<?php
/**
 * Settings API Provider Class
 *
 * Responsible for registering settings for the WordPress REST API.
 *
 * @package Dynamic_Online_Services
 * @subpackage Admin
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Admin;

use TechmireSolutions\DynamicOnlineServices\Settings\Defaults;
use TechmireSolutions\DynamicOnlineServices\Settings\Sanitization;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * SettingsApiProvider class.
 *
 * Single responsibility: Register settings for REST API.
 *
 * @since 1.1.3
 */
class SettingsApiProvider
{

	/**
	 * Register settings for REST API.
	 *
	 * @since 1.1.3
	 * @return void
	 */
	public static function register(): void
	{
		register_setting(
			'Dynamic_Online_Services',
			'dynos_options',
			[
				'type' => 'object',
				'sanitize_callback' => [Sanitization::class, 'sanitize'],
				'default' => Defaults::get_options(),
				'show_in_rest' => [
					'schema' => [
						'type' => 'object',
						'properties' => [],
						'additionalProperties' => true,
					],
				],
			]
		);
	}
}
