<?php
/**
 * Taxonomy Fields Bootstrapper
 *
 * Initializes renderer and saver for taxonomy fields.
 *
 * @package Dynamic_Online_Services
 * @subpackage Taxonomies
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Taxonomies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class TaxonomyFields
 */
class TaxonomyFields {

	/**
	 * Initialize the taxonomy fields components.
	 *
	 * @return void
	 */
	public static function init(): void {
		FieldsRenderer::init();
		FieldsSaver::init();
	}
}
