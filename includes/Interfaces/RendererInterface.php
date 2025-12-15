<?php
/**
 * Renderer Interface
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
 * Interface RendererInterface
 */
interface RendererInterface {

	/**
	 * Render the component.
	 *
	 * @param array $data Data required for rendering.
	 * @return string HTML output.
	 */
	public function render( array $data ): string;
}
