<?php
/**
 * Service Post Type
 *
 * @package Dynamic_Online_Services
 * @subpackage PostTypes
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\PostTypes;

use TechmireSolutions\DynamicOnlineServices\Interfaces\Registrable;

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
	 * @throws \InvalidArgumentException If slug is invalid.
	 */
	public function __construct(string $slug)
	{
		// Sanitize slug
		$sanitized_slug = \sanitize_title($slug);

		// Validate slug is not empty
		if (empty($sanitized_slug)) {
			throw new \InvalidArgumentException(
				'Post type slug cannot be empty'
			);
		}

		// Validate slug length (WordPress limit is 20 characters)
		if (strlen($sanitized_slug) > 20) {
			throw new \InvalidArgumentException(
				sprintf(
					'Post type slug "%s" exceeds maximum length of 20 characters',
					$sanitized_slug
				)
			);
		}

		$this->slug = $sanitized_slug;
	}

	/**
	 * Register the post type.
	 *
	 * @return void
	 */
	public function register(): void
	{
		add_action('init', [$this, 'register_post_type']);
		add_filter('post_updated_messages', [$this, 'updated_messages']);
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
	 * Labels are pulled from plugin settings and can be filtered.
	 *
	 * @return array<string, string>
	 */
	private function get_labels(): array
	{
		// Get settings for post type names
		$options = \TechmireSolutions\DynamicOnlineServices\Helpers\Options::get();

		// Get singular and plural names from settings with fallbacks
		$singular_name = isset($options['service_cpt_singular_name']) && !empty($options['service_cpt_singular_name'])
			? $options['service_cpt_singular_name']
			: 'Service';
		$plural_name = isset($options['service_cpt_plural_name']) && !empty($options['service_cpt_plural_name'])
			? $options['service_cpt_plural_name']
			: 'Services';

		// Allow filtering for maximum flexibility
		$singular_name = apply_filters('dynos_service_singular_name', $singular_name);
		$plural_name = apply_filters('dynos_service_plural_name', $plural_name);

		return [
			'name' => _x($plural_name, 'Post Type General Name', 'dynamic-online-services'),
			'singular_name' => _x($singular_name, 'Post Type Singular Name', 'dynamic-online-services'),
			'menu_name' => $plural_name,
			'name_admin_bar' => $singular_name,
			/* translators: %s: Plural name of the post type */
			'archives' => sprintf(__('%s Archives', 'dynamic-online-services'), $singular_name),
			/* translators: %s: Singular name of the post type */
			'attributes' => sprintf(__('%s Attributes', 'dynamic-online-services'), $singular_name),
			/* translators: %s: Singular name of the post type */
			'parent_item_colon' => sprintf(__('Parent %s:', 'dynamic-online-services'), $singular_name),
			/* translators: %s: Plural name of the post type */
			'all_items' => sprintf(__('All %s', 'dynamic-online-services'), $plural_name),
			/* translators: %s: Singular name of the post type */
			'add_new_item' => sprintf(__('Add New %s', 'dynamic-online-services'), $singular_name),
			'add_new' => __('Add New', 'dynamic-online-services'),
			/* translators: %s: Singular name of the post type */
			'new_item' => sprintf(__('New %s', 'dynamic-online-services'), $singular_name),
			/* translators: %s: Singular name of the post type */
			'edit_item' => sprintf(__('Edit %s', 'dynamic-online-services'), $singular_name),
			/* translators: %s: Singular name of the post type */
			'update_item' => sprintf(__('Update %s', 'dynamic-online-services'), $singular_name),
			/* translators: %s: Singular name of the post type */
			'view_item' => sprintf(__('View %s', 'dynamic-online-services'), $singular_name),
			/* translators: %s: Plural name of the post type */
			'view_items' => sprintf(__('View %s', 'dynamic-online-services'), $plural_name),
			/* translators: %s: Singular name of the post type */
			'search_items' => sprintf(__('Search %s', 'dynamic-online-services'), $singular_name),
			'not_found' => __('Not found', 'dynamic-online-services'),
			'not_found_in_trash' => __('Not found in Trash', 'dynamic-online-services'),
			'featured_image' => __('Featured Image', 'dynamic-online-services'),
			'set_featured_image' => __('Set featured image', 'dynamic-online-services'),
			'remove_featured_image' => __('Remove featured image', 'dynamic-online-services'),
			'use_featured_image' => __('Use as featured image', 'dynamic-online-services'),
			/* translators: %s: Singular name (lowercase) of the post type */
			'insert_into_item' => sprintf(__('Insert into %s', 'dynamic-online-services'), strtolower($singular_name)),
			/* translators: %s: Singular name (lowercase) of the post type */
			'uploaded_to_this_item' => sprintf(__('Uploaded to this %s', 'dynamic-online-services'), strtolower($singular_name)),
			/* translators: %s: Plural name (lowercase) of the post type */
			'items_list' => sprintf(__('%s list', 'dynamic-online-services'), $plural_name),
			/* translators: %s: Plural name of the post type */
			'items_list_navigation' => sprintf(__('%s list navigation', 'dynamic-online-services'), $plural_name),
			/* translators: %s: Plural name (lowercase) of the post type */
			'filter_items_list' => sprintf(__('Filter %s list', 'dynamic-online-services'), strtolower($plural_name)),
		];
	}

	/**
	 * Get post type arguments.
	 *
	 * @param array<string, string> $labels Post type labels.
	 * @return array<string, mixed>
	 */
	private function get_arguments(array $labels): array
	{
		return [
			'label' => __('Service', 'dynamic-online-services'),
			'description' => __('Post Type Description', 'dynamic-online-services'),
			'labels' => $labels,
			'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
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
			'rewrite' => [
				'slug' => \sanitize_title($this->slug),
				'with_front' => false,
			],
		];
	}

	/**
	 * Update messages.
	 *
	 * @param array<int, array<int, string>> $messages Post updated messages.
	 * @return array<int, array<int, string>>
	 */
	public function updated_messages(array $messages): array
	{
		$post = \get_post();
		$post_type_object = \get_post_type_object($this->slug);

		// Return early if post type object doesn't exist or post is null
		if (!$post_type_object || !$post) {
			return $messages;
		}

		// Sanitize revision ID if present.
		$revision_id = \filter_input(INPUT_GET, 'revision', FILTER_VALIDATE_INT);
		$revision_id = $revision_id ? $revision_id : 0;

		// Safe post date access with null check
		$scheduled_date = '';
		if (isset($post->post_date)) {
			$scheduled_date = \date_i18n(
					__('M j, Y @ G:i', 'dynamic-online-services'),
					\strtotime($post->post_date)
				);
		}

		$messages[$this->slug] = [
			0 => '', // Unused. Messages start at index 1.
			1 => __('Service updated.', 'dynamic-online-services'),
			2 => __('Custom field updated.', 'dynamic-online-services'),
			3 => __('Custom field deleted.', 'dynamic-online-services'),
			4 => __('Service updated.', 'dynamic-online-services'),
			/* translators: %s: date and time of the revision */
			5 => $revision_id ? sprintf(__('Service restored to revision from %s', 'dynamic-online-services'), \wp_post_revision_title($revision_id, false)) : false,
			6 => __('Service published.', 'dynamic-online-services'),
			7 => __('Service saved.', 'dynamic-online-services'),
			8 => __('Service submitted.', 'dynamic-online-services'),
			9 => sprintf(
				/* translators: 1: Service schedule date */
				__('Service scheduled for: <strong>%1$s</strong>.', 'dynamic-online-services'),
				$scheduled_date
			),
			10 => __('Service draft updated.', 'dynamic-online-services'),
		];

		return $messages;
	}
}
