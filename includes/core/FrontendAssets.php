<?php
/**
 * Frontend Assets Class
 *
 * Handles initialization of frontend assets.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

use TechmireSolutions\DynamicOnlineServices\Styles\CardStyles;
use TechmireSolutions\DynamicOnlineServices\Styles\FaqStyles;
use TechmireSolutions\DynamicOnlineServices\Styles\WhatsappStyles;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Frontend assets class.
 */
class FrontendAssets
{

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.2.0
	 */
	public function init(): void
	{
		CardStyles::init();
		FaqStyles::init();
		WhatsappStyles::init();
	}
}
