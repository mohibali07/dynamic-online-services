<?php
/**
 * Cards Shortcode Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Shortcodes;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Cards class.
 */
class Cards
{


	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	const TAG = 'service_cards';

	/**
	 * Alias Shortcode tag.
	 *
	 * @var string
	 */
	const ALIAS_TAG = 'course_cards';

	/**
	 * Initialize shortcode.
	 */
	public static function init(): void
	{
		add_shortcode(self::TAG, array(__CLASS__, 'render_callback'));
		add_shortcode(self::ALIAS_TAG, array(__CLASS__, 'render_callback'));
	}

	/**
	 * Shortcode callback.
	 *
	 * @param array|string $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public static function render_callback($atts): string
	{
		$instance = new self();
		return $instance->render(is_array($atts) ? $atts : array());
	}

	/**
	 * Render shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function render(array $atts): string
	{
		// Load dependencies
		$this->load_dependencies();

		// Get default taxonomy slug
		$settings = \TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization::sanitize_cpt_settings();
		$default_taxonomy = isset($settings['taxonomy_slug']) ? $settings['taxonomy_slug'] : 'service-category';

		// Parse attributes
		$atts = shortcode_atts(
			array(
				'ids' => '', // specific post IDs (comma separated)
				'category' => '', // specific category slugs/ids (comma separated)
				'taxonomy' => $default_taxonomy,
				'orderby' => 'date',
				'order' => 'DESC',
				'limit' => '-1',
				'columns' => '3', // Default columns
				'min_width' => '', // Optional override for card min-width
				'show_pagination' => 'false',
				'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
			),
			$atts,
			self::TAG
		);

		// Enqueue styles
		\TechmireSolutions\DynamicOnlineServices\Styles\CardStyles::enqueue(true);

		return $this->generate_output($atts);
	}

	/**
	 * Generate HTML output.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	protected function generate_output(array $atts): string
	{
		// 1. Query Data - use Service
		$query = \TechmireSolutions\DynamicOnlineServices\Services\CardsQueryService::get_query($atts);

		$items = array();
		if ($query->have_posts()) {
			// Optimize: Pre-load all post meta to prevent N+1 queries.
			update_meta_cache('post', wp_list_pluck($query->posts, 'ID'));

			while ($query->have_posts()) {
				$query->the_post();

				// Format data similarly to category items for reuse
				$items[] = array(
					'type' => 'post',
					'id' => get_the_ID(),
					'title' => get_the_title(),
					'url' => get_permalink(),
					'image_url' => get_the_post_thumbnail_url(get_the_ID(), 'medium_large'),
					'image_alt' => get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true),
					'description' => get_the_excerpt(),
				);
			}
			wp_reset_postdata();
		}

		// 2. Render
		if (empty($items)) {
			return '';
		}

		// Use Renderer Class
		$renderer = new \TechmireSolutions\DynamicOnlineServices\Renderers\CardsRenderer();
		return $renderer->render(
			array(
				'items' => $items,
				'atts' => $atts,
			)
		);
	}

	/**
	 * Load required dependencies.
	 */
	protected function load_dependencies(): void
	{
		// No longer needing to manually require files as we use Autoloader and Services
	}
}
