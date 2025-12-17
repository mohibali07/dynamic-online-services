<?php
/**
 * FAQ Accordion Styles
 *
 * Handles enqueuing of FAQ accordion styles with dynamic options.
 *
 * @package Dynamic_Online_Services
 * @subpackage Styles
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Styles;

use TechmireSolutions\DynamicOnlineServices\Helpers\Options;
use TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization;
use TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization as CPTSanitization;
use WP_Post;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * FAQ Styles class.
 */
class FaqStyles
{
	/**
	 * Initialize hooks.
	 *
	 * @since 1.2.0
	 */
	public static function init(): void
	{
		add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue']);
	}

	/**
	 * Enqueue FAQ accordion styles with dynamic options.
	 *
	 * @since 1.2.0
	 */
	public static function enqueue(bool $force = false): void
	{
		// Check if we need to load FAQ styles
		$load_styles = $force;

		// Get post object without using global variable
		$settings = CPTSanitization::sanitize_cpt_settings();
		$service_slug = isset($settings['service_slug']) ? $settings['service_slug'] : 'service';

		if (is_singular($service_slug)) {
			$post = get_queried_object();
			if (!$post || !($post instanceof WP_Post)) {
				$post = get_post();
			}

			if ($post && isset($post->ID)) {
				$faqs = get_post_meta($post->ID, 'service_faqs', true);
				if (is_array($faqs) && !empty($faqs)) {
					$load_styles = true;
				}
			}
		}

		// Check if any post content has the shortcode
		if (!$load_styles) {
			$current_post = get_post();
			if ($current_post instanceof WP_Post && has_shortcode($current_post->post_content, 'service_faqs_accordion')) {
				$load_styles = true;
			}
		}

		if (!$load_styles) {
			return;
		}

		// Get options
		$options = Options::get();

		// Get FAQ settings
		$faq_border_color = Options::get_option($options, 'faq_item_border_color', '#ddd');
		$faq_border_radius = Options::get_option($options, 'faq_item_border_radius', '8px');
		$faq_margin_bottom = Options::get_option($options, 'faq_item_margin_bottom', '15px');
		$faq_box_shadow = Options::get_option($options, 'faq_item_box_shadow', '0 2px 4px rgba(0, 0, 0, 0.05)');
		$faq_question_bg = Options::get_option($options, 'faq_question_bg_color', '#f7f7f7');
		$faq_question_bg_hover = Options::get_option($options, 'faq_question_bg_hover', '#eee');
		$faq_question_text = Options::get_option($options, 'faq_question_text_color', '#333');
		$faq_answer_text = Options::get_option($options, 'faq_answer_text_color', '#333');
		$faq_question_padding = Options::get_option($options, 'faq_question_padding', '15px 20px');
		$faq_question_font_size = Options::get_option($options, 'faq_question_font_size', '1.15rem');
		$faq_icon_font_size = Options::get_option($options, 'faq_icon_font_size', '1.5rem');
		$faq_answer_padding = Options::get_option($options, 'faq_answer_padding', '20px');
		$faq_answer_max_height = Options::get_option($options, 'faq_answer_max_height', '500px');
		$faq_transition_speed = Options::get_option($options, 'faq_transition_speed', '0.4s');

		// Sanitize and escape all CSS values to prevent injection
		$faq_border_color = sanitize_hex_color($faq_border_color);
		$faq_border_radius = Sanitization::escape_css_value($faq_border_radius, 'border-radius');
		$faq_margin_bottom = Sanitization::escape_css_value($faq_margin_bottom, 'margin');
		$faq_box_shadow = Sanitization::escape_css_value($faq_box_shadow, 'box-shadow');
		$faq_question_bg = sanitize_hex_color($faq_question_bg);
		$faq_question_bg_hover = sanitize_hex_color($faq_question_bg_hover);
		$faq_question_text = sanitize_hex_color($faq_question_text);
		$faq_answer_text = sanitize_hex_color($faq_answer_text);
		$faq_question_padding = Sanitization::escape_css_value($faq_question_padding, 'padding');
		$faq_question_font_size = Sanitization::escape_css_value($faq_question_font_size, 'font-size');
		$faq_icon_font_size = Sanitization::escape_css_value($faq_icon_font_size, 'font-size');
		$faq_answer_padding = Sanitization::escape_css_value($faq_answer_padding, 'padding');
		$faq_answer_max_height = Sanitization::escape_css_value($faq_answer_max_height, 'max-height');
		$faq_transition_speed = Sanitization::escape_css_value($faq_transition_speed, '');


		// Enqueue base FAQ styles
		wp_enqueue_style(
			'dynos-faqs-accordion',
			DYNOS_PLUGIN_URL . 'assets/css/faqs-accordion.css',
			[],
			DYNOS_VERSION
		);

		wp_enqueue_script(
			'dynos-faqs-accordion',
			DYNOS_PLUGIN_URL . 'assets/js/faqs-accordion.js',
			['jquery'],
			DYNOS_VERSION,
			true
		);

		// Build dynamic CSS using safe CSS building functions
		$dynamic_css = '.faq-item {';
		$dynamic_css .= Sanitization::build_css_rule('border', '1px solid ' . $faq_border_color);
		$dynamic_css .= Sanitization::build_css_rule('border-radius', $faq_border_radius);
		$dynamic_css .= Sanitization::build_css_rule('margin-bottom', $faq_margin_bottom);
		$dynamic_css .= Sanitization::build_css_rule('box-shadow', $faq_box_shadow);
		$dynamic_css .= '}';

		$dynamic_css .= '.faq-question {';
		$dynamic_css .= Sanitization::build_css_rule('background-color', $faq_question_bg);
		$dynamic_css .= Sanitization::build_css_rule('color', $faq_question_text);
		$dynamic_css .= Sanitization::build_css_rule('padding', $faq_question_padding);
		$dynamic_css .= Sanitization::build_css_rule('font-size', $faq_question_font_size);
		$dynamic_css .= '}';

		$dynamic_css .= '.faq-question:hover {';
		$dynamic_css .= Sanitization::build_css_rule('background-color', $faq_question_bg_hover);
		$dynamic_css .= '}';

		$dynamic_css .= '.faq-icon {';
		$dynamic_css .= Sanitization::build_css_rule('font-size', $faq_icon_font_size);
		$dynamic_css .= Sanitization::build_css_rule('transition', 'transform ' . $faq_transition_speed . ' ease');
		$dynamic_css .= '}';

		$dynamic_css .= '.faq-answer {';
		$dynamic_css .= Sanitization::build_css_rule('color', $faq_answer_text);
		$dynamic_css .= Sanitization::build_css_rule('transition', 'max-height ' . $faq_transition_speed . ' ease-out, padding ' . $faq_transition_speed . ' ease-out');
		$dynamic_css .= '}';

		$dynamic_css .= '.faq-answer.show {';
		$dynamic_css .= Sanitization::build_css_rule('max-height', $faq_answer_max_height);
		$dynamic_css .= Sanitization::build_css_rule('padding', $faq_answer_padding);
		$dynamic_css .= '}';

		// Allow filtering the CSS
		$dynamic_css = apply_filters('dynos_faq_dynamic_css', $dynamic_css, $options);

		wp_add_inline_style('dynos-faqs-accordion', $dynamic_css);
	}
}
