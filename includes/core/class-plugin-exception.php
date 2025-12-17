<?php
/**
 * Plugin Exception Class
 *
 * Custom exception class for plugin-specific errors.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

use Exception; // Import global Exception class

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Plugin exception class.
 *
 * @since 1.1.0
 */
class PluginException extends Exception
{
	/**
	 * Additional context data.
	 *
	 * @var array
	 */
	protected $context = [];

	/**
	 * Error code.
	 *
	 * @var string
	 */
	protected $error_code = '';

	/**
	 * HTTP status code for error response.
	 *
	 * @var int
	 */
	protected $http_status_code = 500;

	/**
	 * Constructor.
	 *
	 * @since 1.1.0
	 * @param string         $message Error message.
	 * @param string         $error_code Error code.
	 * @param int            $code Exception code.
	 * @param array          $context Additional context data.
	 * @param int            $http_status_code HTTP status code.
	 * @param Exception|null $previous Previous exception.
	 */
	public function __construct(
		string $message = '',
		string $error_code = 'dynos_error',
		int $code = 0,
		array $context = [],
		int $http_status_code = 500,
		?Exception $previous = null
	) {
		parent::__construct($message, $code, $previous);
		$this->error_code = $error_code;
		$this->context = $context;
		$this->http_status_code = $http_status_code;
	}

	/**
	 * Get error code.
	 *
	 * @since 1.1.0
	 * @return string Error code.
	 */
	public function get_error_code(): string
	{
		return $this->error_code;
	}

	/**
	 * Get context data.
	 *
	 * @since 1.1.0
	 * @return array Context data.
	 */
	public function get_context(): array
	{
		return $this->context;
	}

	/**
	 * Get HTTP status code.
	 *
	 * @since 1.1.0
	 * @return int HTTP status code.
	 */
	public function get_http_status_code(): int
	{
		return $this->http_status_code;
	}

	/**
	 * Convert exception to array.
	 *
	 * @since 1.1.0
	 * @return array Exception data as array.
	 */
	public function to_array(): array
	{
		return [
			'message' => $this->getMessage(),
			'code' => $this->getCode(),
			'error_code' => $this->error_code,
			'context' => $this->context,
			'http_status_code' => $this->http_status_code,
			'file' => $this->getFile(),
			'line' => $this->getLine(),
		];
	}
}
