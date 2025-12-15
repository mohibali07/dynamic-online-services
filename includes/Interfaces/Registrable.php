<?php
/**
 * Registrable Interface
 *
 * @package Dynamic_Online_Services
 * @subpackage Interfaces
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Interface Registrable
 */
interface Registrable {

	/**
	 * Register the component.
	 *
	 * @return void
	 */
	public function register(): void;
}
