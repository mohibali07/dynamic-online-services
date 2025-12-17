<?php
/**
 * Component Registry Class
 *
 * Responsible for registering and initializing all plugin components.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

use TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization;

if (!\defined('ABSPATH')) {
	exit;
}

/**
 * ComponentRegistry class.
 *
 * Single responsibility: Initialize and register all plugin components.
 *
 * @since 1.1.3
 */
class ComponentRegistry
{

	/**
	 * Initialize all plugin components.
	 *
	 * @since 1.1.3
	 * @return void
	 * @throws \Exception If component initialization fails.
	 */
	public static function init(): void
	{
		ComponentLoader::init();
		ServiceRegistrar::register();
		FaqInitializer::init();
		SettingsInitializer::init();
		ShortcodeInitializer::init();
		I18n::load();
		IntegrationInitializer::init();
	}
}
