<?php
/**
 * Service Keywords Taxonomy
 *
 * @package Dynamic_Online_Services
 * @subpackage Taxonomies
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Taxonomies;

use TechmireSolutions\DynamicOnlineServices\Interfaces\Registrable;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class ServiceKeywordsTaxonomy
 */
class ServiceKeywordsTaxonomy implements Registrable
{

	/**
	 * Taxonomy slug.
	 *
	 * @var string
	 */
	private string $slug = 'service_keywords';

	/**
	 * Post types to associate with.
	 *
	 * @var array<int, string>
	 */
	private array $post_types;

	/**
	 * Constructor.
	 *
	 * @param array<int, string> $post_types Post types to associate.
	 */
	public function __construct(array $post_types)
	{
		$this->post_types = $post_types;
	}

	/**
	 * Register the taxonomy.
	 *
	 * @return void
	 */
	public function register(): void
	{
		add_action('init', array($this, 'register_taxonomy'));
	}

	/**
	 * Register taxonomy.
	 *
	 * @return void
	 */
	public function register_taxonomy(): void
	{
		$labels = array(
			'name' => _x('Service Keywords', 'Taxonomy General Name', 'dynamic-online-services'),
			'singular_name' => _x('Service Keyword', 'Taxonomy Singular Name', 'dynamic-online-services'),
			'menu_name' => __('Keywords', 'dynamic-online-services'),
			'all_items' => __('All Keywords', 'dynamic-online-services'),
			'parent_item' => __('Parent Keyword', 'dynamic-online-services'),
			'parent_item_colon' => __('Parent Keyword:', 'dynamic-online-services'),
			'new_item_name' => __('New Keyword Name', 'dynamic-online-services'),
			'add_new_item' => __('Add New Keyword', 'dynamic-online-services'),
			'edit_item' => __('Edit Keyword', 'dynamic-online-services'),
			'update_item' => __('Update Keyword', 'dynamic-online-services'),
			'view_item' => __('View Keyword', 'dynamic-online-services'),
			'separate_items_with_commas' => __('Separate keywords with commas', 'dynamic-online-services'),
			'add_or_remove_items' => __('Add or remove keywords', 'dynamic-online-services'),
			'choose_from_most_used' => __('Choose from the most used', 'dynamic-online-services'),
			'popular_items' => __('Popular Keywords', 'dynamic-online-services'),
			'search_items' => __('Search Keywords', 'dynamic-online-services'),
			'not_found' => __('Not Found', 'dynamic-online-services'),
			'no_terms' => __('No keywords', 'dynamic-online-services'),
			'items_list' => __('Keywords list', 'dynamic-online-services'),
			'items_list_navigation' => __('Keywords list navigation', 'dynamic-online-services'),
		);

		$args = array(
			'labels' => $labels,
			'hierarchical' => false,
			'public' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud' => false,
			'show_in_rest' => true,
			'rewrite' => false,
		);

		register_taxonomy($this->slug, $this->post_types, $args);
	}
}
