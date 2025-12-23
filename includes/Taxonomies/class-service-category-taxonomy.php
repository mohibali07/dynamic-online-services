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
	private function get_labels(): array {
		return array(
			'name'                       => _x( 'Service Categories', 'Taxonomy General Name', 'dynamic-online-services' ),
			'singular_name'              => _x( 'Service Category', 'Taxonomy Singular Name', 'dynamic-online-services' ),
			'menu_name'                  => __( 'Categories', 'dynamic-online-services' ),
			'all_items'                  => __( 'All Categories', 'dynamic-online-services' ),
			'parent_item'                => __( 'Parent Category', 'dynamic-online-services' ),
			'parent_item_colon'          => __( 'Parent Category:', 'dynamic-online-services' ),
			'new_item_name'              => __( 'New Category Name', 'dynamic-online-services' ),
			'add_new_item'               => __( 'Add New Category', 'dynamic-online-services' ),
			'edit_item'                  => __( 'Edit Category', 'dynamic-online-services' ),
			'update_item'                => __( 'Update Category', 'dynamic-online-services' ),
			'view_item'                  => __( 'View Category', 'dynamic-online-services' ),
			'separate_items_with_commas' => __( 'Separate categories with commas', 'dynamic-online-services' ),
			'add_or_remove_items'        => __( 'Add or remove categories', 'dynamic-online-services' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'dynamic-online-services' ),
			'popular_items'              => __( 'Popular Categories', 'dynamic-online-services' ),
			'search_items'               => __( 'Search Categories', 'dynamic-online-services' ),
			'not_found'                  => __( 'Not Found', 'dynamic-online-services' ),
			'no_terms'                   => __( 'No categories', 'dynamic-online-services' ),
			'items_list'                 => __( 'Categories list', 'dynamic-online-services' ),
			'items_list_navigation'      => __( 'Categories list navigation', 'dynamic-online-services' ),
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
