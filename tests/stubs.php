<?php
/**
 * Stubs for WordPress functions not mocked by WP_Mock
 */

if (!defined('DAY_IN_SECONDS')) {
    define('DAY_IN_SECONDS', 86400);
}
if (!defined('HOUR_IN_SECONDS')) {
    define('HOUR_IN_SECONDS', 3600);
}
if (!defined('MINUTE_IN_SECONDS')) {
    define('MINUTE_IN_SECONDS', 60);
}

if (!function_exists('current_user_can')) {
    function current_user_can($capability, ...$args) {
        return false;
    }
}
if (!function_exists('maybe_serialize')) {
    function maybe_serialize($data) {
        if ($data instanceof \Closure) {
            return $data;
        }
        if (is_array($data) || is_object($data)) {
            return serialize($data);
        }
        return $data;
    }
}
if (!function_exists('wp_parse_args')) {
    function wp_parse_args($args, $defaults = '') {
        if (is_object($args)) {
            $r = get_object_vars($args);
        } elseif (is_array($args)) {
            $r = &$args;
        } else {
            wp_parse_str($args, $r);
        }

        if (is_array($defaults)) {
            return array_merge($defaults, $r);
        }
        return $r;
    }
}
if (!function_exists('plugin_basename')) {
    function plugin_basename($file) {
        return basename($file);
    }
}

if (!function_exists('wp_cache_flush_group')) {
    function wp_cache_flush_group($group) {}
}
if (!function_exists('wp_parse_str')) {
    function wp_parse_str($string, &$array) {
        parse_str($string, $array);
    }
}
if (!function_exists('add_shortcode')) {
    function add_shortcode($tag, $callback) {
        return true;
    }
}
if (!function_exists('get_current_user_id')) {
    function get_current_user_id() {
        return 0;
    }
}
if (!function_exists('apply_filters')) {
    function apply_filters($tag, $value) {
        return $value;
    }
}
if (!function_exists('do_action')) {
    function do_action($tag, ...$arg) {}
}

if (!function_exists('get_query_var')) {
    function get_query_var($var, $default = '') {
        return $default;
    }
}

if (!function_exists('wp_strip_all_tags')) {
    function wp_strip_all_tags($string, $remove_breaks = false) {
        return strip_tags($string);
    }
}
if (!function_exists('sanitize_title')) {
    function sanitize_title($title, $fallback_title = '', $context = 'save') {
        return strtolower(str_replace(' ', '-', $title));
    }
}
if (!function_exists('absint')) {
    function absint($maybeint) {
        return abs(intval($maybeint));
    }
}
if (!function_exists('get_post')) {
    function get_post($post = null, $output = OBJECT, $filter = 'raw') {
        // Return null by default so tests can mock it if needed
        // Or if stub provided, use it.
        return null;
    }
}
if (!defined('OBJECT')) {
    define('OBJECT', 'OBJECT');
}
if (!function_exists('get_post_type')) {
    function get_post_type($post = null) {
        return false;
    }
}
if (!function_exists('get_term')) {
    function get_term($term, $taxonomy = '', $output = OBJECT, $filter = 'raw') {
        return null;
    }
}
if (!function_exists('is_wp_error')) {
    function is_wp_error($thing) {
        return $thing instanceof \WP_Error;
    }
}
if (!class_exists('WP_Error')) {
    class WP_Error {}
}
if (!class_exists('WP_Query')) {
    class WP_Query {
        public static $injected_posts = [];
        public $posts = [];
        public $post;
        public $current_post = -1;
        public function __construct($args = []) {
            $this->posts = self::$injected_posts;
            if (class_exists('TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy')) {
                \TechmireSolutions\DynamicOnlineServices\Helpers\MockSpy::record('WP_Query::__construct', func_get_args());
            }
        }
        public function have_posts() {
            return ($this->current_post + 1) < count($this->posts);
        }
        public function the_post() {
            $this->current_post++;
            $this->post = $this->posts[$this->current_post];
            return $this->post;
        }
        public function get_posts() { return $this->posts; }
        public function reset_postdata() { $this->current_post = -1; }
    }
}
if (!function_exists('wp_attachment_is_image')) {
    function wp_attachment_is_image($post = null) {
        return false;
    }
}
if (!function_exists('wp_list_pluck')) {
    function wp_list_pluck($list, $field, $index_key = null) {
        return [];
    }
}
if (!function_exists('get_the_ID')) {
    function get_the_ID() { return 0; }
}
if (!function_exists('get_the_title')) {
    function get_the_title($post = 0) { return ''; }
}
if (!function_exists('get_permalink')) {
    function get_permalink($post = 0, $leavename = false) { return ''; }
}
if (!function_exists('get_the_post_thumbnail_url')) {
    function get_the_post_thumbnail_url($post = null, $size = 'post-thumbnail') { return ''; }
}
if (!function_exists('get_post_thumbnail_id')) {
    function get_post_thumbnail_id($post = null) { return 0; }
}
if (!function_exists('get_post_meta')) {
    function get_post_meta($post_id, $key = '', $single = false) { return ''; }
}
if (!function_exists('get_the_excerpt')) {
    function get_the_excerpt($post = null) { return ''; }
}
if (!function_exists('taxonomy_exists')) {
    function taxonomy_exists($taxonomy) {
        return true;
    }
}
if (!function_exists('wp_reset_postdata')) {
    function wp_reset_postdata() {}
}
if (!function_exists('wp_json_encode')) {
    function wp_json_encode($data, $options = 0, $depth = 512) {
        return json_encode($data, $options, $depth);
    }
}
if (!function_exists('wp_using_ext_object_cache')) {
    function wp_using_ext_object_cache() {
        return false;
    }
}
if (!function_exists('get_post_type')) {
    function get_post_type($post = null) {
        return 'post';
    }
}
if (!function_exists('get_object_taxonomies')) {
    function get_object_taxonomies($object, $output = 'names') {
        return [];
    }
}

if (!function_exists('shortcode_atts')) {
    function shortcode_atts($pairs, $atts, $shortcode = '') {
        return array_merge((array)$pairs, (array)$atts);
    }
}
if (!function_exists('is_singular')) {
    function is_singular($post_types = '') {
        return false;
    }
}
if (!function_exists('is_tax')) {
    function is_tax($taxonomy = '', $term = '') {
        return false;
    }
}
if (!function_exists('get_post')) {
    function get_post($post = null, $output = OBJECT, $filter = 'raw') {
        return null;
    }
}
if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($str) {
        return is_string($str) ? trim($str) : '';
    }
}
if (!function_exists('sanitize_hex_color')) {
    function sanitize_hex_color($color) {
        return $color;
    }
}
if (!function_exists('has_shortcode')) {
    function has_shortcode($content, $tag) {
        return false;
    }
}
if (!function_exists('wp_enqueue_style')) {
    function wp_enqueue_style($handle, $src = '', $deps = [], $ver = false, $media = 'all') {
        return true;
    }
}
if (!function_exists('wp_add_inline_style')) {
    function wp_add_inline_style($handle, $data) {
        return true;
    }
}
if (!function_exists('wp_register_style')) {
    function wp_register_style($handle, $src = '', $deps = [], $ver = false, $media = 'all') {
        return true;
    }
}
if (!function_exists('is_object')) {
    function is_object($var) {
        return is_object($var);
    }
}
if (!function_exists('esc_attr')) {
    function esc_attr($text) {
        return (string) $text;
    }
}

if (!function_exists('get_option')) {
    function get_option($option, $default = false) {
        return $default;
    }
}
if (!function_exists('get_transient')) {
    function get_transient($transient) {
        return false;
    }
}
if (!function_exists('set_transient')) {
    function set_transient($transient, $value, $expiration = 0) {
        return true;
    }
}
if (!function_exists('delete_transient')) {
    function delete_transient($transient) {
        return true;
    }
}

if (!function_exists('update_meta_cache')) {
    function update_meta_cache($meta_type, $object_ids) {
        return true;
    }
}
if (!function_exists('flush_rewrite_rules')) {
    function flush_rewrite_rules($hard = true) {}
}
