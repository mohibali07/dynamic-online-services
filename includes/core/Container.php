<?php
/**
 * Container Class
 *
 * A simple Dependency Injection Container.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 * @since 1.1.4
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Container class.
 */
class Container
{
	/**
	 * Services storage.
	 *
	 * @var array<string, mixed>
	 */
	private array $services = [];

	/**
	 * Shared instances storage.
	 *
	 * @var array<string, object>
	 */
	private array $instances = [];

	/**
	 * Register a service.
	 *
	 * @param string $id Service Identifier.
	 * @param mixed  $concrete Concrete implementation or factory closure.
	 */
	public function register(string $id, $concrete): void
	{
		$this->services[$id] = $concrete;
	}

	/**
	 * Get a service.
	 *
	 * @param string $id Service Identifier.
	 * @return mixed
	 * @throws \Exception If service not found.
	 */
	public function get(string $id)
	{
		if (isset($this->instances[$id])) {
			return $this->instances[$id];
		}

		if (!isset($this->services[$id])) {
			throw new \Exception("Service not found: {$id}");
		}

		$concrete = $this->services[$id];

		if (is_callable($concrete)) {
			// Pass container to the factory for recursive resolution
			$instance = $concrete($this);
		} else {
			$instance = $concrete;
		}

		$this->instances[$id] = $instance;

		return $instance;
	}

	/**
	 * Check if service exists.
	 *
	 * @param string $id Service Identifier.
	 * @return bool
	 */
	public function has(string $id): bool
	{
		return isset($this->services[$id]) || isset($this->instances[$id]);
	}
}
