<?php
/**
 * Post Type Settings Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings\Sections
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Settings\Sections;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PostTypeSettings class.
 */
class PostTypeSettings {

	/**
	 * Register settings.
	 */
	public static function register(): void {
		add_settings_field(
			'service_post_type_slug',
			_x( 'Service Post Type Slug', 'Settings field label', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_post_type_section',
			array(
				'name'        => 'service_post_type_slug',
				'default'     => 'services',
				'placeholder' => 'e.g., services, my-services',
				'description' => __( 'URL slug for the service post type. Change requires flushing rewrite rules.', 'dynamic-online-services' ),
				'label_for'   => 'service_post_type_slug',
			)
		);

		add_settings_field(
			'service_taxonomy_slug',
			_x( 'Service Taxonomy Slug', 'Settings field label', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_post_type_section',
			array(
				'name'        => 'service_taxonomy_slug',
				'default'     => 'service-category',
				'placeholder' => 'e.g., service-category, category',
				'description' => __( 'URL slug for the service category taxonomy. Change requires flushing rewrite rules.', 'dynamic-online-services' ),
				'label_for'   => 'service_taxonomy_slug',
			)
		);

		add_settings_field(
			'service_menu_position',
			_x( 'Service Menu Position', 'Settings field label', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_post_type_section',
			array(
				'name'        => 'service_menu_position',
				'default'     => '',
				'placeholder' => 'e.g., 20, 30 (leave empty for default)',
				'description' => __( 'Menu position in WordPress admin. Lower numbers appear first. Leave empty for default position.', 'dynamic-online-services' ),
				'label_for'   => 'service_menu_position',
			)
		);

		add_settings_field(
			'service_menu_icon',
			_x( 'Service Menu Icon', 'Settings field label', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_post_type_section',
			array(
				'name'        => 'service_menu_icon',
				'default'     => 'dashicons-admin-customizer',
				'placeholder' => 'e.g., dashicons-admin-customizer',
				'description' => __( 'Dashicon class name for the menu icon. See <a href="https://developer.wordpress.org/resource/dashicons/" target="_blank">Dashicons</a>.', 'dynamic-online-services' ),
				'label_for'   => 'service_menu_icon',
			)
		);

		add_settings_field(
			'service_cpt_singular_name',
			_x( 'Service Singular Name', 'Settings field label', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_post_type_section',
			array(
				'name'        => 'service_cpt_singular_name',
				'default'     => 'Service',
				'placeholder' => 'e.g., Service, Course',
				'description' => __( 'Singular name for the post type.', 'dynamic-online-services' ),
				'label_for'   => 'service_cpt_singular_name',
			)
		);

		add_settings_field(
			'service_cpt_plural_name',
			_x( 'Service Plural Name', 'Settings field label', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_post_type_section',
			array(
				'name'        => 'service_cpt_plural_name',
				'default'     => 'Services',
				'placeholder' => 'e.g., Services, Courses',
				'description' => __( 'Plural name for the post type.', 'dynamic-online-services' ),
				'label_for'   => 'service_cpt_plural_name',
			)
		);

		add_settings_field(
			'service_tax_singular_name',
			_x( 'Category Singular Name', 'Settings field label', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_post_type_section',
			array(
				'name'        => 'service_tax_singular_name',
				'default'     => 'Service Category',
				'placeholder' => 'e.g., Category, Topic',
				'description' => __( 'Singular name for the taxonomy.', 'dynamic-online-services' ),
				'label_for'   => 'service_tax_singular_name',
			)
		);

		add_settings_field(
			'service_tax_plural_name',
			_x( 'Category Plural Name', 'Settings field label', 'dynamic-online-services' ),
			'dynos_text_field_callback',
			'Dynamic_Online_Services',
			'dynos_post_type_section',
			array(
				'name'        => 'service_tax_plural_name',
				'default'     => 'Service Categories',
				'placeholder' => 'e.g., Categories, Topics',
				'description' => __( 'Plural name for the taxonomy.', 'dynamic-online-services' ),
				'label_for'   => 'service_tax_plural_name',
			)
		);
	}
}
