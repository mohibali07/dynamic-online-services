<?php
/**
 * Marketing Settings Configuration
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings\Config
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Settings\Config;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Marketing Config class.
 */
class Marketing
{

	/**
	 * Get mapping.
	 *
	 * @return array
	 */
	public static function get_map(): array
	{
		return array(
			'id'     => 'dynos_marketing_section',
			'title'  => __('Marketing Widget Settings', 'dynamic-online-services'),
			'fields' => array(
				'marketing_match_threshold' => array(
					'title'    => _x('Match Threshold', 'Settings field label', 'dynamic-online-services'),
					'callback' => 'dynos_number_field_callback',
					'default'  => 3,
					'args'     => array(
						'min'         => 1,
						'max'         => 50,
						'step'        => 1,
						'description' => __('Minimum score required for a service to match. Higher = stricter matches.', 'dynamic-online-services'),
					),
				),
				'marketing_stop_words'      => array(
					'title'    => _x('Stop Words', 'Settings field label', 'dynamic-online-services'),
					'callback' => 'dynos_textarea_field_callback',
					'default'  => 'the, and, or, but, is, a, an, in, on, of, for, to, at, by, best, top',
					'args'     => array(
						'rows'        => 3,
						'description' => __('Comma-separated list of words to ignore during keyword matching.', 'dynamic-online-services'),
					),
				),
			),
		);
	}
}
