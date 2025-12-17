<?php
/**
 * Options Migrator Class
 *
 * Responsible for migrating plugin options from previous versions.
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
 * OptionsMigrator class.
 *
 * Single responsibility: Migrate plugin options from old versions.
 *
 * @since 1.1.3
 */
class OptionsMigrator
{

	/**
	 * Migrate old plugin options to current version.
	 *
	 * Handles migration from:
	 * - ss_options → dynos_options
	 * - sos_options → dynos_options
	 *
	 * @since 1.1.3
	 * @return void
	 */
	public static function migrate(): void
	{
		self::migrate_from_ss_options();
		self::migrate_from_sos_options();
	}

	/**
	 * Migrate from ss_options to dynos_options.
	 *
	 * @since 1.1.3
	 * @return void
	 */
	private static function migrate_from_ss_options(): void
	{
		$old_ss_options = get_option('ss_options', false);
		if (false !== $old_ss_options && false === get_option('dynos_options', false)) {
			update_option('dynos_options', $old_ss_options);
		}
	}

	/**
	 * Migrate from sos_options to dynos_options.
	 *
	 * @since 1.1.3
	 * @return void
	 */
	private static function migrate_from_sos_options(): void
	{
		$old_sos_options = get_option('sos_options', false);
		if (false !== $old_sos_options && false === get_option('dynos_options', false)) {
			update_option('dynos_options', $old_sos_options);
		}
	}
}
