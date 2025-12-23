<?php
/**
 * Advanced Configuration
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings\Config
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Settings\Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Advanced configuration class.
 */
class Advanced {

	/**
	 * Get advanced settings map.
	 *
	 * @return array Settings map.
	 */
	public static function get_map(): array {
		return array(
			'id'     => 'dynos_advanced_section',
			'title'  => __( 'Advanced Configuration', 'dynamic-online-services' ),
			'fields' => array(
				'breakpoint_tablet'         => array(
					'title'    => __( 'Tablet Breakpoint', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '768px',
					'args'     => array(
						'placeholder' => 'e.g., 768px, 1024px',
						'description' => __( 'CSS max-width for tablet devices. Used in responsive media queries.', 'dynamic-online-services' ),
					),
				),
				'breakpoint_mobile'         => array(
					'title'    => __( 'Mobile Breakpoint', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '480px',
					'args'     => array(
						'placeholder' => 'e.g., 480px, 600px',
						'description' => __( 'CSS max-width for mobile devices. Used in responsive media queries.', 'dynamic-online-services' ),
					),
				),
				'max_grid_columns'          => array(
					'title'    => __( 'Maximum Grid Columns', 'dynamic-online-services' ),
					'callback' => 'dynos_number_field_callback',
					'default'  => '6',
					'args'     => array(
						'min'         => '1',
						'max'         => '12',
						'step'        => '1',
						'description' => __( 'Maximum number of columns in grid layouts.', 'dynamic-online-services' ),
					),
				),
				'min_grid_columns'          => array(
					'title'    => __( 'Minimum Grid Columns', 'dynamic-online-services' ),
					'callback' => 'dynos_number_field_callback',
					'default'  => '1',
					'args'     => array(
						'min'         => '1',
						'max'         => '6',
						'step'        => '1',
						'description' => __( 'Minimum number of columns in grid layouts.', 'dynamic-online-services' ),
					),
				),
				'max_taxonomy_depth'        => array(
					'title'    => __( 'Maximum Taxonomy Depth', 'dynamic-online-services' ),
					'callback' => 'dynos_number_field_callback',
					'default'  => '10',
					'args'     => array(
						'min'         => '1',
						'max'         => '20',
						'step'        => '1',
						'description' => __( 'Maximum hierarchy depth for category permalinks. Prevents infinite loops.', 'dynamic-online-services' ),
					),
				),
				'default_excerpt_length'    => array(
					'title'    => __( 'Default Excerpt Length', 'dynamic-online-services' ),
					'callback' => 'dynos_number_field_callback',
					'default'  => '20',
					'args'     => array(
						'min'         => '5',
						'max'         => '100',
						'step'        => '1',
						'description' => __( 'Number of words in automatically generated excerpts.', 'dynamic-online-services' ),
					),
				),
				'max_posts_per_page'        => array(
					'title'    => __( 'Maximum Posts Per Page', 'dynamic-online-services' ),
					'callback' => 'dynos_number_field_callback',
					'default'  => '1000',
					'args'     => array(
						'min'         => '10',
						'max'         => '5000',
						'step'        => '10',
						'description' => __( 'Maximum posts to query at once. Prevents performance issues.', 'dynamic-online-services' ),
					),
				),
				'default_grid_min_width'    => array(
					'title'    => __( 'Default Grid Minimum Width', 'dynamic-online-services' ),
					'callback' => 'dynos_text_field_callback',
					'default'  => '280px',
					'args'     => array(
						'placeholder' => 'e.g., 280px, 18rem',
						'description' => __( 'Minimum width for grid items. Controls responsive wrapping.', 'dynamic-online-services' ),
					),
				),
				'google_font_hosting'       => array(
					'title'    => __( 'Google Font Hosting Method', 'dynamic-online-services' ),
					'callback' => 'dynos_radio_field_callback',
					'default'  => 'remote',
					'args'     => array(
						'options'     => array(
							'remote' => __( 'Remote (Google CDN - Faster but sends data to Google)', 'dynamic-online-services' ),
							'local'  => __( 'Local (GDPR Compliant - Fonts hosted on your server)', 'dynamic-online-services' ),
						),
						'description' => __( 'Choose how Google Fonts are loaded. Local hosting improves GDPR compliance but may be slower on first load.', 'dynamic-online-services' ),
					),
				),
			),
		);
	}
}
