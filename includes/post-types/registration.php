<?php
/**
 * Post Type Registration
 *
 * Handles registration of custom post types and taxonomies.
 *
 * @package Dynamic_Online_Services
 * @subpackage Post_Types
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the 'Service' Custom Post Type.
 *
 * @since 1.1.0
 * @param string $service_slug   Post type slug.
 * @param mixed  $menu_position  Menu position (int or null).
 * @param string $menu_icon      Menu icon class name.
 * @return void
 */
function doc_register_service_post_type($service_slug, $menu_position, $menu_icon): void {
    // Allow filtering labels before registration
    $labels = apply_filters('doc_service_cpt_labels', array(
        'name'               => _x('Services', 'Post type general name', 'dynamic-online-services'),
        'singular_name'      => _x('Service', 'Post type singular name', 'dynamic-online-services'),
        'menu_name'          => _x('Services', 'Admin menu name', 'dynamic-online-services'),
        'add_new'            => _x('Add New', 'Service', 'dynamic-online-services'),
        'add_new_item'       => _x('Add New Service', 'Service post type', 'dynamic-online-services'),
        'edit_item'          => _x('Edit Service', 'Service post type', 'dynamic-online-services'),
        'new_item'           => _x('New Service', 'Service post type', 'dynamic-online-services'),
        'view_item'          => _x('View Service', 'Service post type', 'dynamic-online-services'),
        'search_items'       => _x('Search Services', 'Service post type', 'dynamic-online-services'),
        'not_found'          => _x('No services found', 'Service post type', 'dynamic-online-services'),
        'not_found_in_trash' => _x('No services found in trash', 'Service post type', 'dynamic-online-services'),
        'all_items'          => _x('All Services', 'Service post type', 'dynamic-online-services'),
    ));

    // Build post type arguments with hierarchical permalink structure
    // The rewrite slug includes %services_category% placeholder which will be replaced
    // with actual category hierarchy in doc_service_permalink_structure()
    $args = apply_filters('doc_service_cpt_args', array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array(
            'slug'       => $service_slug . '/%services_category%',
            'with_front' => false, // Don't prepend front base (e.g., /blog/)
        ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => $menu_position,
        'menu_icon'           => $menu_icon,
        'supports'            => array('title', 'thumbnail'),
        'show_in_rest'        => true,
    ));

    $registered = register_post_type('service', $args);

    // Log error if registration fails
    if (is_wp_error($registered)) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            doc_log_error(
                'Failed to register service post type: ' . $registered->get_error_message(),
                'error',
                array('error_code' => $registered->get_error_code())
            );
        }
        // Fire action for error handling
        do_action('doc_post_type_registration_error', $registered, 'service');
    }
}

/**
 * Register the 'Services Category' Taxonomy.
 *
 * @since 1.1.0
 * @param string $taxonomy_slug Taxonomy slug.
 * @return void
 */
function doc_register_services_category_taxonomy($taxonomy_slug): void {
    // Allow filtering labels before registration
    $labels = apply_filters('doc_services_category_tax_labels', array(
        'name'              => _x('Services Categories', 'Taxonomy general name', 'dynamic-online-services'),
        'singular_name'     => _x('Services Category', 'Taxonomy singular name', 'dynamic-online-services'),
        'search_items'      => _x('Search Categories', 'Service category taxonomy', 'dynamic-online-services'),
        'all_items'         => _x('All Categories', 'Service category taxonomy', 'dynamic-online-services'),
        'parent_item'       => _x('Parent Category', 'Service category taxonomy', 'dynamic-online-services'),
        'parent_item_colon' => _x('Parent Category:', 'Service category taxonomy', 'dynamic-online-services'),
        'edit_item'         => _x('Edit Category', 'Service category taxonomy', 'dynamic-online-services'),
        'update_item'       => _x('Update Category', 'Service category taxonomy', 'dynamic-online-services'),
        'add_new_item'      => _x('Add New Category', 'Service category taxonomy', 'dynamic-online-services'),
        'new_item_name'     => _x('New Category Name', 'Service category taxonomy', 'dynamic-online-services'),
        'menu_name'         => _x('Categories', 'Service category taxonomy', 'dynamic-online-services'),
    ));

    $args = apply_filters('doc_services_category_tax_args', array(
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'hierarchical'      => true,
        'rewrite'           => array(
            'slug'         => $taxonomy_slug,
            'hierarchical' => true,
        ),
        'show_in_rest'      => true,
    ));

    $registered = register_taxonomy('services_category', array('service'), $args);

    // Log error if registration fails
    if (is_wp_error($registered)) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            doc_log_error(
                'Failed to register services_category taxonomy: ' . $registered->get_error_message(),
                'error',
                array('error_code' => $registered->get_error_code())
            );
        }
        // Fire action for error handling
        do_action('doc_taxonomy_registration_error', $registered, 'services_category');
    }
}

/**
 * Register the 'Service' Custom Post Type and 'Services Category' Taxonomy.
 *
 * @since 1.1.0
 * @return void
 */
function doc_register_services_cpt_and_taxonomy(): void {
    // Sanitize and validate settings
    $settings = doc_sanitize_cpt_settings();

    // Register post type
    doc_register_service_post_type(
        $settings['service_slug'],
        $settings['menu_position'],
        $settings['menu_icon']
    );

    // Register taxonomy
    doc_register_services_category_taxonomy($settings['taxonomy_slug']);
}
add_action('init', 'doc_register_services_cpt_and_taxonomy', 10);

