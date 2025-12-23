<?php
/**
 * PHPUnit Bootstrap
 */

declare(strict_types=1);

fwrite(STDERR, "DEBUG: Bootstrap Start\n");

if (!defined('ABSPATH')) {
    define('ABSPATH', sys_get_temp_dir() . '/wordpress/');
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
WP_Mock::bootstrap();

// Define plugin constants
if (!defined('DYNOS_PLUGIN_DIR')) {
    define('DYNOS_PLUGIN_DIR', dirname(dirname(__FILE__)) . '/');
}
if (!defined('DAY_IN_SECONDS')) define('DAY_IN_SECONDS', 86400);
if (!defined('HOUR_IN_SECONDS')) define('HOUR_IN_SECONDS', 3600);
if (!defined('DYNOS_PLUGIN_URL')) define('DYNOS_PLUGIN_URL', 'http://example.com/wp-content/plugins/dynamic-online-services/');
if (!defined('DYNOS_VERSION')) define('DYNOS_VERSION', '1.1.3');

// Manually require the Autoloader
require_once DYNOS_PLUGIN_DIR . 'includes/class-autoloader.php';
\TechmireSolutions\DynamicOnlineServices\Autoloader::run();

// Load Proxy Stubs (NOW they delegate to WP_Mock's functions)
require_once __DIR__ . '/proxy-stubs.php';

// Define Baseline WP Mocks (Global Namespace) - Let WP_Mock handle hooks/shortcodes natively
WP_Mock::userFunction('get_option', ['return' => false]);
WP_Mock::userFunction('get_transient', ['return' => false]);
WP_Mock::userFunction('set_transient', ['return' => true]);
WP_Mock::userFunction('delete_transient', ['return' => true]);
WP_Mock::userFunction('get_query_var', ['return' => '']);
WP_Mock::userFunction('get_post', ['return' => null]);
WP_Mock::userFunction('get_posts', ['return' => []]);
WP_Mock::userFunction('get_term', ['return' => null]);
WP_Mock::userFunction('get_term_by', ['return' => null]);
WP_Mock::userFunction('get_terms', ['return' => []]);
WP_Mock::userFunction('is_tax', ['return' => false]);
WP_Mock::userFunction('is_wp_error', ['return' => false]);
WP_Mock::userFunction('wp_parse_args', ['return' => []]); // Note: individual tests should override with logic
WP_Mock::userFunction('shortcode_atts', ['return' => []]); // Note: individual tests should override with logic
WP_Mock::userFunction('wp_list_pluck', ['return' => []]);
WP_Mock::userFunction('wp_reset_postdata', ['return' => true]);
WP_Mock::userFunction('get_queried_object', ['return' => null]);
WP_Mock::userFunction('sanitize_hex_color', ['return_arg' => 0]);
WP_Mock::userFunction('wp_json_encode', ['return' => '{"json":true}']);
WP_Mock::userFunction('plugin_basename', ['return' => 'plugin-dir/plugin-file.php']);
WP_Mock::userFunction('deactivate_plugins', ['return' => true]);
WP_Mock::userFunction('add_option', ['return' => true]);
WP_Mock::userFunction('update_option', ['return' => true]);
WP_Mock::userFunction('delete_option', ['return' => true]);
WP_Mock::userFunction('register_activation_hook', ['return' => true]);
WP_Mock::userFunction('register_deactivation_hook', ['return' => true]);
WP_Mock::userFunction('wp_die', ['return' => true]);
WP_Mock::userFunction('admin_url', ['return' => 'http://example.com/wp-admin/']);
WP_Mock::userFunction('get_admin_url', ['return' => 'http://example.com/wp-admin/']);
WP_Mock::userFunction('register_post_type', ['return' => true]);
WP_Mock::userFunction('register_taxonomy', ['return' => true]);
WP_Mock::userFunction('register_block_type', ['return' => true]);
WP_Mock::userFunction('flush_rewrite_rules', ['return' => true]);
WP_Mock::userFunction('get_post_type_object', ['return' => null]);
WP_Mock::userFunction('get_object_taxonomies', ['return' => []]);
WP_Mock::userFunction('date_i18n', ['return' => 'Date']);
WP_Mock::userFunction('wp_post_revision_title', ['return' => 'Revision']);

// Dynos Helper Stubs (to satisfy function_exists checks in namespaced code)
WP_Mock::userFunction('dynos_validate_term_object', ['return_arg' => 0]);
WP_Mock::userFunction('dynos_get_category_shortcode_child_categories', ['return' => []]);
WP_Mock::userFunction('dynos_get_category_shortcode_services', ['return' => ['items'=>[], 'total_pages'=>1, 'current_page'=>1]]);
WP_Mock::userFunction('dynos_render_category_shortcode_items', ['return' => '']);
WP_Mock::userFunction('dynos_validate_category_shortcode_attributes', ['return_arg' => 0]);
WP_Mock::userFunction('dynos_log_error', ['return' => true]);
WP_Mock::userFunction('dynos_validate_orderby', ['return_arg' => 0]);
WP_Mock::userFunction('dynos_sanitize_cpt_settings', ['return_arg' => 0]);
WP_Mock::userFunction('dynos_get_pagination_html', ['return' => '']);

if (!function_exists('plugin_dir_path')) { function plugin_dir_path($file) { return DYNOS_PLUGIN_DIR; } }
if (!function_exists('esc_html')) { function esc_html($text) { return $text; } }
if (!function_exists('__')) { function __($text, $domain = 'default') { return $text; } }
if (!function_exists('sanitize_title')) { function sanitize_title($title, $fallback = '', $context = 'save') { return strtolower(str_replace(' ', '-', (string)$title)); } }
if (!function_exists('sanitize_text_field')) { function sanitize_text_field($str) { return is_string($str) ? trim($str) : (string)$str; } }

if (!function_exists('is_admin')) { function is_admin() { return false; } }
if (!function_exists('absint')) { function absint($val) { return abs((int)$val); } }
if (!function_exists('esc_attr')) { function esc_attr($t) { return $t; } }
if (!function_exists('esc_url')) { function esc_url($t) { return $t; } }
if (!function_exists('esc_html__')) { function esc_html__($t) { return $t; } }
if (!function_exists('wp_unslash')) { function wp_unslash($v) { return $v; } }
if (!function_exists('_x')) { function _x($t, $c, $d='default') { return $t; } }
if (!function_exists('wp_strip_all_tags')) { function wp_strip_all_tags($t) { return strip_tags($t); } }
if (!function_exists('wp_kses_post')) { function wp_kses_post($t) { return $t; } }
if (!function_exists('wp_attachment_is_image')) { function wp_attachment_is_image($id) { return true; } }

// Essential classes
if (!class_exists('WP_Post')) {
    class WP_Post {
        public $ID = 0;
        public $post_type = 'post';
    }
}
if (!class_exists('WP_Term')) {
    class WP_Term {
        public $term_id = 0;
        public $taxonomy = '';
    }
}
if (!class_exists('WP_Error')) {
    class WP_Error {
        public function __construct($code = '', $message = '', $data = '') {}
    }
}
if (!class_exists('WP_Query')) {
    class WP_Query {
        public $posts = array();
        public $post_count = 0;
        public function __construct($args = array()) {}
        public function have_posts() { return !empty($this->posts); }
        public function the_post() {}
        public function reset_postdata() {}
    }
}

fwrite(STDERR, "DEBUG: Bootstrap End\n");
