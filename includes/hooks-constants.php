<?php
/**
 * Constants Filter Hooks Documentation
 *
 * Documents all filter hooks related to plugin constants for developer reference.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 * @since 1.2.0
 */


// This file is for documentation purposes only and is never included.
if (!defined('ABSPATH')) {
	exit;
}


/**
 * Filter the tablet breakpoint value.
 *
 * This breakpoint is used in responsive CSS media queries to target
 * tablet devices.
 *
 * @since 1.2.0
 *
 * @param string $breakpoint Default tablet breakpoint ('768px').
 * @return string Modified breakpoint value (e.g., '1024px').
 *
 * @example
 * add_filter('dynos_breakpoint_tablet', function($default) {
 *     return '1024px'; // Change tablet breakpoint to 1024px
 * });
 */
$dynos_breakpoint_tablet = apply_filters('dynos_breakpoint_tablet', '768px');

/**
 * Filter the mobile breakpoint value.
 *
 * This breakpoint is used in responsive CSS media queries to target
 * mobile devices.
 *
 * @since 1.2.0
 *
 * @param string $breakpoint Default mobile breakpoint ('480px').
 * @return string Modified breakpoint value (e.g., '600px').
 *
 * @example
 * add_filter('dynos_breakpoint_mobile', function($default) {
 *     return '600px'; // Change mobile breakpoint to 600px
 * });
 */
$dynos_breakpoint_mobile = apply_filters('dynos_breakpoint_mobile', '480px');

/**
 * Filter the maximum grid columns.
 *
 * Controls the maximum number of columns in grid layouts throughout
 * the plugin.
 *
 * @since 1.2.0
 *
 * @param int $columns Default maximum columns (6).
 * @return int Modified maximum columns.
 *
 * @example
 * add_filter('dynos_max_grid_columns', function($default) {
 *     return 4; // Limit to 4 columns maximum
 * });
 */
$dynos_max_grid_columns = apply_filters('dynos_max_grid_columns', 6);

/**
 * Filter the minimum grid columns.
 *
 * Controls the minimum number of columns in grid layouts.
 *
 * @since 1.2.0
 *
 * @param int $columns Default minimum columns (1).
 * @return int Modified minimum columns.
 *
 * @example
 * add_filter('dynos_min_grid_columns', function($default) {
 *    return 2; // Force minimum 2 columns
 * });
 */
$dynos_min_grid_columns = apply_filters('dynos_min_grid_columns', 1);

/**
 * Filter the maximum taxonomy hierarchy depth.
 *
 * Prevents infinite loops when traversing category hierarchies for
 * permalink generation. This is a safety limit.
 *
 * @since 1.2.0
 *
 * @param int $depth Default maximum depth (10).
 * @return int Modified maximum depth.
 *
 * @example
 * add_filter('dynos_max_taxonomy_depth', function($default) {
 *     return 5; // Limit to 5 levels deep
 * });
 */
$dynos_max_taxonomy_depth = apply_filters('dynos_max_taxonomy_depth', 10);

/**
 * Filter the default excerpt length.
 *
 * Controls the number of words in automatically generated excerpts.
 *
 * @since 1.2.0
 *
 * @param int $length Default excerpt length in words (20).
 * @return int Modified excerpt length.
 *
 * @example
 * add_filter('dynos_default_excerpt_length', function($default) {
 *     return 30; // Longer excerpts with 30 words
 * });
 */
$dynos_default_excerpt_length = apply_filters('dynos_default_excerpt_length', 20);

/**
 * Filter the maximum posts per page.
 *
 * This safety limit prevents excessive database queries that could
 * impact performance.
 *
 * @since 1.2.0
 *
 * @param int $max Default maximum posts (1000).
 * @return int Modified maximum posts.
 *
 * @example
 * add_filter('dynos_max_posts_per_page', function($default) {
 *     return 500; // Reduce to 500 posts maximum
 * });
 */
$dynos_max_posts_per_page = apply_filters('dynos_max_posts_per_page', 1000);

/**
 * Filter the default grid minimum width.
 *
 * Controls the minimum width for grid items, affecting responsive
 * wrapping behavior.
 *
 * @since 1.2.0
 *
 * @param string $width Default minimum width ('280px').
 * @return string Modified minimum width (e.g., '320px', '18rem').
 *
 * @example
 * add_filter('dynos_default_grid_min_width', function($default) {
 *     return '320px'; // Wider minimum width
 * });
 */
$dynos_default_grid_min_width = apply_filters('dynos_default_grid_min_width', '280px');

/**
 * Filter the service post type singular name.
 *
 * Allows changing the singular name displayed throughout the admin
 * interface (e.g., "Service" to "Course").
 *
 * @since 1.2.0
 *
 * @param string $name Default singular name from settings.
 * @return string Modified singular name.
 *
 * @example
 * add_filter('dynos_service_singular_name', function($default) {
 *     return 'Course'; // Change to "Course"
 * });
 */
$dynos_service_singular_name = apply_filters('dynos_service_singular_name', 'Service');

/**
 * Filter the service post type plural name.
 *
 * Allows changing the plural name displayed throughout the plugin
 * and admin interface.
 *
 * @since 1.2.0
 *
 * @param string $name Default plural name from settings.
 * @return string Modified plural name.
 *
 * @example
 * add_filter('dynos_service_plural_name', function($default) {
 *     return 'Courses'; // Change to "Courses"
 * });
 */
$dynos_service_plural_name = apply_filters('dynos_service_plural_name', 'Services');
