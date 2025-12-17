<?php
/**
 * Internationalization Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * I18n class.
 */
class I18n
{

	/**
	 * Load textdomain.
	 *
	 * @since 1.1.4
	 * @return void
	 */
	public static function load(): void
	{
		load_plugin_textdomain(
			'dynamic-online-services',
			false,
			dirname(DYNOS_PLUGIN_BASENAME) . '/languages'
		);
	}
}
