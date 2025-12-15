<?php
/**
 * Prefix Migration Handler
 *
 * Handles migration from old 'doc_' prefix to new 'dynos_' prefix.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PrefixMigration
 *
 * Handles database option migration from old prefix to new prefix.
 */
class PrefixMigration {


	/**
	 * Migrate options from old prefix to new prefix.
	 *
	 * @return void
	 */
	public static function migrate_options(): void {
		// Check if already migrated.
		if ( self::is_migrated() ) {
			return;
		}

		// Migrate main options.
		$old_options = get_option( 'dynos_options', false );
		if ( false !== $old_options ) {
			update_option( 'dynos_options', $old_options );
		}

		// Migrate slug tracking options.
		$old_service_slug = get_option( 'dynos_previous_service_slug', false );
		if ( false !== $old_service_slug ) {
			update_option( 'dynos_previous_service_slug', $old_service_slug );
		}

		$old_taxonomy_slug = get_option( 'dynos_previous_taxonomy_slug', false );
		if ( false !== $old_taxonomy_slug ) {
			update_option( 'dynos_previous_taxonomy_slug', $old_taxonomy_slug );
		}

		// Set migration flag.
		update_option( 'dynos_prefix_migration_complete', true );
		update_option( 'dynos_prefix_migration_date', current_time( 'mysql' ) );
	}

	/**
	 * Check if migration has been completed.
	 *
	 * @return bool True if migrated, false otherwise.
	 */
	public static function is_migrated(): bool {
		return (bool) get_option( 'dynos_prefix_migration_complete', false );
	}

	/**
	 * Rollback migration (for testing purposes).
	 *
	 * @return void
	 */
	public static function rollback(): void {
		delete_option( 'dynos_options' );
		delete_option( 'dynos_previous_service_slug' );
		delete_option( 'dynos_previous_taxonomy_slug' );
		delete_option( 'dynos_prefix_migration_complete' );
		delete_option( 'dynos_prefix_migration_date' );
	}

	/**
	 * Get migration status information.
	 *
	 * @return array<string, mixed> Migration status details.
	 */
	public static function get_status(): array {
		return array(
			'migrated'       => self::is_migrated(),
			'migration_date' => get_option( 'dynos_prefix_migration_date', '' ),
			'old_options'    => get_option( 'dynos_options', false ),
			'new_options'    => get_option( 'dynos_options', false ),
		);
	}
}
