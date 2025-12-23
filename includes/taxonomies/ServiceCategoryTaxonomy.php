<?php
/**
 * Service Category Taxonomy
 *
 * @package Dynamic_Online_Services
 * @subpackage Taxonomies
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Taxonomies;

use TechmireSolutions\DynamicOnlineServices\Interfaces\Registrable;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class ServiceCategoryTaxonomy
 */
class ServiceCategoryTaxonomy implements Registrable {

	/**
	 * Taxonomy slug.
	 *
	 * @var string
	 */
	private string $slug;

	/**
	 * Post types to attach to.
	 *
	 * @var array<int, string>
	 */
	private array $post_types;

	/**
	 * Constructor.
	 *
	 * @param string             $slug       Taxonomy slug.
	 * @param array<int, string> $post_types Post types to attach to.
	 */
	public function __construct( string $slug, array $post_types ) {
		$this->slug       = $slug;
		$this->post_types = $post_types;
	}

	/**
	 * Register the taxonomy.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'register_taxonomy' ) );
	}

	/**
	 * Render the taxonomy registration.
	 *
	 * @return void
	 */
	public function register_taxonomy(): void {
		$labels = $this->get_labels();
		$args   = $this->get_arguments( $labels );

		\register_taxonomy( $this->slug, $this->post_types, $args );
	}

	/**
	 * Get taxonomy labels.
	 *
	 * @return array<string, string>
	 */
	/**
	 * Get taxonomy labels.
	 *
	 * @return array<string, string>
	 */
	private function get_labels(): array {
		$options = \TechmireSolutions\DynamicOnlineServices\Helpers\Options::get();

		$singular_name = isset($options['service_tax_singular_name']) && !empty($options['service_tax_singular_name'])
			? $options['service_tax_singular_name']
			: 'Service Category';

		$plural_name = isset($options['service_tax_plural_name']) && !empty($options['service_tax_plural_name'])
			? $options['service_tax_plural_name']
			: 'Service Categories';

		// phpcs:disable WordPress.WP.I18n.NonSingularStringLiteralText -- Dynamic taxonomy names from settings
		return array(
			'name'                       => _x( $plural_name, 'Taxonomy General Name', 'dynamic-online-services' ),
			'singular_name'              => _x( $singular_name, 'Taxonomy Singular Name', 'dynamic-online-services' ),
			// phpcs:enable WordPress.WP.I18n.NonSingularStringLiteralText
			'menu_name'                  => $plural_name,
			/* translators: %s: Plural name of the taxonomy */
			'all_items'                  => sprintf( __( 'All %s', 'dynamic-online-services' ), $plural_name ),
			/* translators: %s: Singular name of the taxonomy */
			'parent_item'                => sprintf( __( 'Parent %s', 'dynamic-online-services' ), $singular_name ),
			/* translators: %s: Singular name of the taxonomy */
			'parent_item_colon'          => sprintf( __( 'Parent %s:', 'dynamic-online-services' ), $singular_name ),
			/* translators: %s: Singular name of the taxonomy */
			'new_item_name'              => sprintf( __( 'New %s Name', 'dynamic-online-services' ), $singular_name ),
			/* translators: %s: Singular name of the taxonomy */
			'add_new_item'               => sprintf( __( 'Add New %s', 'dynamic-online-services' ), $singular_name ),
			/* translators: %s: Singular name of the taxonomy */
			'edit_item'                  => sprintf( __( 'Edit %s', 'dynamic-online-services' ), $singular_name ),
			/* translators: %s: Singular name of the taxonomy */
			'update_item'                => sprintf( __( 'Update %s', 'dynamic-online-services' ), $singular_name ),
			/* translators: %s: Singular name of the taxonomy */
			'view_item'                  => sprintf( __( 'View %s', 'dynamic-online-services' ), $singular_name ),
			/* translators: %s: Plural name of the taxonomy */
			'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'dynamic-online-services' ), strtolower( $plural_name ) ),
			/* translators: %s: Plural name of the taxonomy */
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'dynamic-online-services' ), strtolower( $plural_name ) ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'dynamic-online-services' ),
			/* translators: %s: Plural name of the taxonomy */
			'popular_items'              => sprintf( __( 'Popular %s', 'dynamic-online-services' ), $plural_name ),
			/* translators: %s: Plural name of the taxonomy */
			'search_items'               => sprintf( __( 'Search %s', 'dynamic-online-services' ), $plural_name ),
			'not_found'                  => __( 'Not Found', 'dynamic-online-services' ),
			/* translators: %s: Plural name of the taxonomy */
			'no_terms'                   => sprintf( __( 'No %s', 'dynamic-online-services' ), strtolower( $plural_name ) ),
			/* translators: %s: Plural name of the taxonomy */
			'items_list'                 => sprintf( __( '%s list', 'dynamic-online-services' ), $plural_name ),
			/* translators: %s: Plural name of the taxonomy */
			'items_list_navigation'      => sprintf( __( '%s list navigation', 'dynamic-online-services' ), $plural_name ),
		);
	}

	/**
	 * Get taxonomy arguments.
	 *
	 * @param array<string, string> $labels Taxonomy labels.
	 * @return array<string, mixed>
	 */
	private function get_arguments( array $labels ): array {
		return array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => false,
			'rewrite'           => array(
				'slug'         => $this->slug,
				'hierarchical' => true,
			),
			'show_in_rest'      => true,
		);
	}
}
