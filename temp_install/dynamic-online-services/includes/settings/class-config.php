<?php
/**
 * Settings Configuration Class
 *
 * Defines the structure of all settings sections and fields.
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Settings;

use TechmireSolutions\DynamicOnlineServices\Settings\Config\Hero;
use TechmireSolutions\DynamicOnlineServices\Settings\Config\Cards;
use TechmireSolutions\DynamicOnlineServices\Settings\Config\Faq;
use TechmireSolutions\DynamicOnlineServices\Settings\Config\Advanced;
use TechmireSolutions\DynamicOnlineServices\Settings\Config\Whatsapp;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Config class.
 */
class Config {

	/**
	 * Get all settings sections and fields.
	 *
	 * @return array Configuration array.
	 */
	public static function get_map(): array {
		return array(
			// Post Type & Taxonomy Settings
			'post_type' => array(
				'id'     => 'dynos_post_type_section',
				'title'  => __( 'Post Type & Taxonomy Settings', 'dynamic-online-services' ),
				'fields' => array(
					'service_post_type_slug'    => array(
						'title'       => _x( 'Service Post Type Slug', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => 'services',
						'args'        => array(
							'placeholder' => 'e.g., services, my-services',
							'description' => __( 'URL slug for the service post type. Change requires flushing rewrite rules.', 'dynamic-online-services' ),
						),
					),
					'service_taxonomy_slug'     => array(
						'title'       => _x( 'Service Taxonomy Slug', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => 'service-category',
						'args'        => array(
							'placeholder' => 'e.g., service-category, category',
							'description' => __( 'URL slug for the service category taxonomy. Change requires flushing rewrite rules.', 'dynamic-online-services' ),
						),
					),
					'service_menu_position'     => array(
						'title'       => _x( 'Service Menu Position', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => '',
						'args'        => array(
							'placeholder' => 'e.g., 20, 30 (leave empty for default)',
							'description' => __( 'Menu position in WordPress admin. Lower numbers appear first. Leave empty for default position.', 'dynamic-online-services' ),
						),
					),
					'service_menu_icon'         => array(
						'title'       => _x( 'Service Menu Icon', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => 'dashicons-admin-customizer',
						'args'        => array(
							'placeholder' => 'e.g., dashicons-admin-customizer',
							'description' => __( 'Dashicon class name for the menu icon. See <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank">Dashicons</a>.', 'dynamic-online-services' ),
						),
					),
					'service_cpt_singular_name' => array(
						'title'       => _x( 'Service Singular Name', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => 'Service',
						'args'        => array(
							'placeholder' => 'e.g., Service, Course',
							'description' => __( 'Singular name for the post type.', 'dynamic-online-services' ),
						),
					),
					'service_cpt_plural_name'   => array(
						'title'       => _x( 'Service Plural Name', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => 'Services',
						'args'        => array(
							'placeholder' => 'e.g., Services, Courses',
							'description' => __( 'Plural name for the post type.', 'dynamic-online-services' ),
						),
					),
					'service_tax_singular_name' => array(
						'title'       => _x( 'Category Singular Name', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => 'Service Category',
						'args'        => array(
							'placeholder' => 'e.g., Category, Topic',
							'description' => __( 'Singular name for the taxonomy.', 'dynamic-online-services' ),
						),
					),
					'service_tax_plural_name'   => array(
						'title'       => _x( 'Category Plural Name', 'Settings field label', 'dynamic-online-services' ),
						'callback'    => 'dynos_text_field_callback',
						'default'     => 'Service Categories',
						'args'        => array(
							'placeholder' => 'e.g., Categories, Topics',
							'description' => __( 'Plural name for the taxonomy.', 'dynamic-online-services' ),
						),
					),
				),
			),

			// Hero Section Settings
			'hero'      => Hero::get_map(),

			// Service Cards Settings
			'cards'     => Cards::get_map(),

			// FAQ Accordion Settings
			'faq'       => Faq::get_map(),

			// Advanced Configuration Settings
			'advanced'  => Advanced::get_map(),

			// WhatsApp Settings
			'whatsapp'  => Whatsapp::get_map(),
		);
	}
}
