<?php
/**
 * Service Post Type
 *
 * @package Dynamic_Online_Services
 * @subpackage PostTypes
 */

declare(strict_types=1);

namespace DynamicOnlineServices\PostTypes;

use DynamicOnlineServices\Interfaces\Registrable;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class ServicePostType
 */
class ServicePostType implements Registrable
{


	/**
	 * Post type slug.
	 *
	 * @var string
	 */
	private string $slug;

	/**
	 * Constructor.
	 *
	 * @param string $slug Post type slug.
	 */
	public function __construct(string $slug)
	{
		$this->slug = $slug;
	}

	/**
	 * Register the post type.
	 *
	 * @return void
	 */
	public function register(): void
	{
		add_action('init', array($this, 'register_post_type'));
		add_filter('post_updated_messages', array($this, 'updated_messages'));
	}

	/**
	 * Render the post type registration.
	 *
	 * @return void
	 */
	public function register_post_type(): void
	{
		$labels = $this->get_labels();
		$args = $this->get_arguments($labels);

		register_post_type($this->slug, $args);
	}

	/**
	 * Get post type labels.
	 *
	 * @return array<string, string>
	 */
	private function get_labels(): array
	{
		return array(
			'name' => _x('Services', 'Post Type General Name', 'dynamic-online-services'),
			'singular_name' => _x('Service', 'Post Type Singular Name', 'dynamic-online-services'),
			'menu_name' => __('Services', 'dynamic-online-services'),
			'name_admin_bar' => __('Service', 'dynamic-online-services'),
			'archives' => __('Service Archives', 'dynamic-online-services'),
			'attributes' => __('Service Attributes', 'dynamic-online-services'),
			'parent_item_colon' => __('Parent Service:', 'dynamic-online-services'),
			'all_items' => __('All Services', 'dynamic-online-services'),
			'add_new_item' => __('Add New Service', 'dynamic-online-services'),
			'add_new' => __('Add New', 'dynamic-online-services'),
			'new_item' => __('New Service', 'dynamic-online-services'),
			'edit_item' => __('Edit Service', 'dynamic-online-services'),
			'update_item' => __('Update Service', 'dynamic-online-services'),
			'view_item' => __('View Service', 'dynamic-online-services'),
			'view_items' => __('View Services', 'dynamic-online-services'),
			'search_items' => __('Search Service', 'dynamic-online-services'),
			'not_found' => __('Not found', 'dynamic-online-services'),
			'not_found_in_trash' => __('Not found in Trash', 'dynamic-online-services'),
			'featured_image' => __('Featured Image', 'dynamic-online-services'),
			'set_featured_image' => __('Set featured image', 'dynamic-online-services'),
			'remove_featured_image' => __('Remove featured image', 'dynamic-online-services'),
			'use_featured_image' => __('Use as featured image', 'dynamic-online-services'),
			'insert_into_item' => __('Insert into service', 'dynamic-online-services'),
			'uploaded_to_this_item' => __('Uploaded to this service', 'dynamic-online-services'),
			'items_list' => __('Services list', 'dynamic-online-services'),
			'items_list_navigation' => __('Services list navigation', 'dynamic-online-services'),
			'filter_items_list' => __('Filter services list', 'dynamic-online-services'),
		);
	}

	/**
	 * Get post type arguments.
	 *
	 * @param array<string, string> $labels Post type labels.
	 * @return array<string, mixed>
	 */
	private function get_arguments(array $labels): array
	{
		return array(
			'label' => __('Service', 'dynamic-online-services'),
			'description' => __('Post Type Description', 'dynamic-online-services'),
			'labels' => $labels,
			'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
			'hierarchical' => false,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 5,
			'menu_icon' => 'dashicons-grid-view',
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'capability_type' => 'post',
			'show_in_rest' => true,
			'rewrite' => array(
				'slug' => $this->slug . '/%services_category%',
				'with_front' => false,
			),
		);
	}

	/**
	 * Update messages.
	 *
	 * @param array<int, array<int, string>> $messages Post updated messages.
	 * @return array<int, array<int, string>>
	 */
	public function updated_messages(array $messages): array
	{
		$post = get_post();
		$post_type_object = get_post_type_object($this->slug);

		if (!$post_type_object) {
			return $messages;
		}

		// Sanitize revision ID if present.
		$revision_id = filter_input(INPUT_GET, 'revision', FILTER_VALIDATE_INT);
		$revision_id = $revision_id ? $revision_id : 0;

		$messages[$this->slug] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __('Service updated.', 'dynamic-online-services'),
			2 => __('Custom field updated.', 'dynamic-online-services'),
			3 => __('Custom field deleted.', 'dynamic-online-services'),
			4 => __('Service updated.', 'dynamic-online-services'),
			/* translators: %s: date and time of the revision */
			5 => $revision_id ? sprintf(__('Service restored to revision from %s', 'dynamic-online-services'), wp_post_revision_title($revision_id, false)) : false,
			6 => __('Service published.', 'dynamic-online-services'),
			7 => __('Service saved.', 'dynamic-online-services'),
			8 => __('Service submitted.', 'dynamic-online-services'),
			9 => sprintf(
				/* translators: 1: Service schedule date */
				__('Service scheduled for: <strong>%1$s</strong>.', 'dynamic-online-services'),
				// translators: Publish box date format, see http://php.net/date
				date_i18n(__('M j, Y @ G:i', 'dynamic-online-services'), strtotime($post->post_date))
			),
			10 => __('Service draft updated.', 'dynamic-online-services'),
		);

		return $messages;
	}
}
