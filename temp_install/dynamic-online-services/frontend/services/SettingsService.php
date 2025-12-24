<?php
/**
 * Settings Service
 *
 * Encapsulates global settings retrieval.
 *
 * @package Dynamic_Online_Services
 * @subpackage Services
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Services;

defined( 'ABSPATH' ) || exit;

use TechmireSolutions\DynamicOnlineServices\Settings\Defaults;

/**
 * Class SettingsService
 */
class SettingsService
{

	/**
	 * Instance.
	 *
	 * @var SettingsService
	 */
	private static $instance;

	/**
	 * Settings array.
	 *
	 * @var array
	 */
	private $options;

	/**
	 * Get instance.
	 *
	 * @return SettingsService
	 */
	public static function get_instance(): SettingsService
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct()
	{
		// Load options using the helper to ensure caching is respected
		$this->options = \TechmireSolutions\DynamicOnlineServices\Helpers\Options::get();
	}

	/**
	 * Get all options.
	 *
	 * @return array
	 */
	public function get_options(): array
	{
		return $this->options;
	}

	/**
	 * Get a specific option value.
	 *
	 * @param string $key Option key.
	 * @param mixed  $default Default value.
	 * @return mixed
	 */
	public function get_option(string $key, $default = '')
	{
		return \TechmireSolutions\DynamicOnlineServices\Helpers\Options::get_option($this->options, $key, $default);
	}

	/**
	 * Refresh options (e.g., after save).
	 */
	public function refresh(): void
	{
	}
}
