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
use TechmireSolutions\DynamicOnlineServices\Cpt\Sanitization as CPTSanitization;
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
		add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue'));
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

		// Defaults
		$defaults = array(
			'faq_item_border_color'     => '#ddd',
			'faq_item_border_radius'    => '8px',
			'faq_item_margin_bottom'    => '15px',
			'faq_item_box_shadow'       => '0 2px 4px rgba(0, 0, 0, 0.05)',
			'faq_question_bg_color'     => '#f7f7f7',
			'faq_question_bg_hover'     => '#eee',
			'faq_question_text_color'   => '#333',
			'faq_answer_text_color'     => '#333',
			'faq_question_padding'      => '15px 20px',
			'faq_question_font_size'    => '1.15rem',
			'faq_icon_font_size'        => '1.5rem',
			'faq_answer_padding'        => '20px',
			'faq_answer_max_height'     => '500px',
			'faq_transition_speed'      => '0.4s',
		);

		// Get settings
		$settings = array();
		foreach ($defaults as $key => $default) {
			$settings[$key] = Options::get_option($options, $key, $default);
		}

		// Enqueue base FAQ styles
		wp_enqueue_style(
			'sos-faqs-accordion',
			DYNOS_PLUGIN_URL . 'assets/css/faqs-accordion.css',
			array(),
			DYNOS_VERSION
		);

		wp_enqueue_script(
			'sos-faqs-accordion',
			DYNOS_PLUGIN_URL . 'assets/js/faqs-accordion.js',
			array('jquery'),
			DYNOS_VERSION,
			true
		);

		// Sanitize Colors
		$colors = array(
			'faq_item_border_color', 'faq_question_bg_color', 'faq_question_bg_hover',
			'faq_question_text_color', 'faq_answer_text_color'
		);
		foreach ($colors as $color_key) {
			$settings[$color_key] = sanitize_hex_color($settings[$color_key]);
		}

		// Build CSS Variables Map
		$css_vars = array(
			'--faq-border-color'        => $settings['faq_item_border_color'],
			'--faq-radius'              => Sanitization::escape_css_value($settings['faq_item_border_radius'], 'border-radius'),
			'--faq-margin-bottom'       => Sanitization::escape_css_value($settings['faq_item_margin_bottom'], 'margin'),
			'--faq-shadow'              => Sanitization::escape_css_value($settings['faq_item_box_shadow'], 'box-shadow'),
			'--faq-q-bg'                => $settings['faq_question_bg_color'],
			'--faq-q-bg-hover'          => $settings['faq_question_bg_hover'],
			'--faq-q-text'              => $settings['faq_question_text_color'],
			'--faq-a-text'              => $settings['faq_answer_text_color'],
			'--faq-q-padding'           => Sanitization::escape_css_value($settings['faq_question_padding'], 'padding'),
			'--faq-q-size'              => Sanitization::escape_css_value($settings['faq_question_font_size'], 'font-size'),
			'--faq-icon-size'           => Sanitization::escape_css_value($settings['faq_icon_font_size'], 'font-size'),
			'--faq-a-padding'           => Sanitization::escape_css_value($settings['faq_answer_padding'], 'padding'),
			'--faq-a-max-height'        => Sanitization::escape_css_value($settings['faq_answer_max_height'], 'max-height'),
			'--faq-speed'               => Sanitization::escape_css_value($settings['faq_transition_speed'], ''),
		);

		// Generate CSS Variables block
		$css_string = ":root {\n";
		foreach ($css_vars as $var => $value) {
			if (!empty($value)) {
				$css_string .= "\t{$var}: {$value};\n";
			}
		}
		$css_string .= "}\n";

		// Allow filtering the CSS variables
		$css_string = apply_filters('dynos_faq_css_variables', $css_string, $css_vars);

		wp_add_inline_style('sos-faqs-accordion', $css_string);
	}
}
