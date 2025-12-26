<?php
/**
 * Marketing Widget Shortcode
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Shortcodes;

use TechmireSolutions\DynamicOnlineServices\Marketing\MatchingEngine;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class MarketingWidgetShortcode
 */
class MarketingWidgetShortcode
{

	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	private const TAG = 'dynos_marketing_widget';

	/**
	 * Init shortcode.
	 *
	 * @return void
	 */
	public static function init(): void
	{
		add_shortcode(self::TAG, array(__CLASS__, 'render'));
	}

	/**
	 * Render shortcode.
	 *
	 * @param array|string $atts Attributes.
	 * @return string
	 */
	public static function render($atts): string
	{
		// Only run on singular posts/pages to extract context
		if (!is_singular()) {
			return '';
		}

		global $post;

		// Extract context from current post
		$context = array(
			'title' => get_the_title($post),
			'url' => get_permalink($post),
			'body' => $post->post_content,
			'h1' => get_the_title($post),
			'post_id' => $post->ID,
		);

		// Initialize matching engine
		$engine = new MatchingEngine();
		// Find up to 5 matches
		$match_ids = $engine->find_matches($context, 5);

		if (empty($match_ids)) {
			return '';
		}

		// Render the carousel or single item
		ob_start();
		if (count($match_ids) === 1) {
			self::render_promo_box($match_ids[0]);
		} else {
			self::render_carousel($match_ids);
		}
		return ob_get_clean();
	}

	/**
	 * Render a single promo box.
	 *
	 * @param int $service_id Service ID.
	 * @return void
	 */
	public static function render_promo_box(int $service_id): void
	{
		self::render_slide_content($service_id);
	}

	/**
	 * Render the content of a slide/box.
	 *
	 * @param int $service_id Service ID.
	 * @return void
	 */
	private static function render_slide_content(int $service_id): void
	{
		$title = get_the_title($service_id);
		$permalink = get_permalink($service_id);
		$excerpt = get_the_excerpt($service_id);
		$thumbnail = get_the_post_thumbnail_url($service_id, 'medium');

		?>
		<div class="dynos-marketing-card" style="border: 2px solid var(--wp--preset--color--primary, #0073aa); padding: 20px; border-radius: 8px; margin: 20px 0; background-color: #f9f9f9; display: flex; gap: 20px; align-items: center; width: 100%; box-sizing: border-box;">
			<?php if ($thumbnail) : ?>
				<div class="dynos-mw-image" style="flex: 0 0 100px;">
					<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($title); ?>" style="width: 100%; height: auto; border-radius: 4px;">
				</div>
			<?php endif; ?>
			<div class="dynos-mw-content">
				<h3 style="margin-top: 0;"><?php echo esc_html(__('Recommended for You', 'dynamic-online-services')); ?></h3>
				<h4 style="margin: 5px 0;"><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a></h4>
				<p style="margin-bottom: 10px;"><?php echo wp_kses_post($excerpt); ?></p>
				<a href="<?php echo esc_url($permalink); ?>" class="button button-primary"><?php echo esc_html(__('View Service', 'dynamic-online-services')); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Render a carousel for multiple items.
	 *
	 * @param array<int> $service_ids Array of service IDs.
	 * @return void
	 */
	public static function render_carousel(array $service_ids): void
	{
		$carousel_id = 'dynos-carousel-' . wp_rand();
		?>
		<style>
			.dynos-carousel-container {
				overflow: hidden;
				width: 100%;
				position: relative;
			}
			.dynos-carousel-track {
				display: flex;
				overflow-x: auto;
				scroll-snap-type: x mandatory;
				scroll-behavior: smooth;
				-webkit-overflow-scrolling: touch;
				gap: 16px;
				padding-bottom: 15px; /* Space for scrollbar if visible */
			}
			.dynos-carousel-slide {
				flex: 0 0 100%;
				scroll-snap-align: center;
				/* Ideally matches the card style */
			}
			/* Hide scrollbar */
			.dynos-carousel-track::-webkit-scrollbar {
				height: 6px;
			}
			.dynos-carousel-track::-webkit-scrollbar-thumb {
				background: #ccc;
				border-radius: 3px;
			}
		</style>
		<div class="dynos-carousel-container" id="<?php echo esc_attr($carousel_id); ?>">
			<div class="dynos-carousel-track">
				<?php foreach ($service_ids as $id) : ?>
					<div class="dynos-carousel-slide">
						<?php self::render_slide_content($id); ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div style="text-align: center; margin-top: -10px; font-size: 0.8em; color: #666;">
				<?php echo esc_html(__('Swipe to see more recommendations', 'dynamic-online-services')); ?>
			</div>
		</div>
		<?php
	}
}
