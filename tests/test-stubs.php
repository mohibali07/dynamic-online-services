<?php


declare(strict_types=1);

if (!function_exists('add_shortcode')) {
    function add_shortcode($tag, $callback) {
        if (class_exists('SpyHelper')) {
            \SpyHelper::record_shortcode($tag, $callback);
        }
        return true;
    }
}
if (!function_exists('add_action')) {
    function add_action($tag, $callback, $priority = 10, $accepted_args = 1) {
        if (class_exists('SpyHelper')) {
            # echo "DEBUG: Global add_action HIT for $tag\n";
            \SpyHelper::record_action($tag, $callback, $priority, $accepted_args);
        }
        $GLOBALS['spy_actions'][$tag][] = ['callback' => $callback];
        return true;
    }
}
if (!function_exists('add_filter')) {
    function add_filter($tag, $callback, $priority = 10, $accepted_args = 1) {
        if (class_exists('SpyHelper')) {
            \SpyHelper::record_filter($tag, $callback, $priority, $accepted_args);
        }
        return true;
    }
}

if (!function_exists('get_current_user_id')) {
    function get_current_user_id() {
        return 0;
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

if (!function_exists('plugin_dir_path')) {
    function plugin_dir_path($file) {
        return '/path/to/plugin/';
    }
}

if (!function_exists('wp_parse_str')) {
    function wp_parse_str($string, &$array) {
        parse_str($string, $array);
    }
}

if (!function_exists('wp_strip_all_tags')) {
    function wp_strip_all_tags($string, $remove_breaks = false) {
        return strip_tags($string);
    }
}

if (!function_exists('sanitize_title')) {
    function sanitize_title($title) {
        $title = strtolower($title);
        $title = str_replace(' ', '-', $title);
        return preg_replace('/[^a-z0-9\-]/', '', $title);
    }
}

if (!function_exists('absint')) {
    function absint($maybeint) {
        return abs(intval($maybeint));
    }
}

if (!function_exists('is_wp_error')) {
    function is_wp_error($thing) {
        return false;
    }
}

if (!function_exists('register_activation_hook')) {
    function register_activation_hook($file, $function) {
        return true;
    }
}

if (!function_exists('register_deactivation_hook')) {
    function register_deactivation_hook($file, $function) {
        return true;
    }
}

if (!function_exists('class_exists')) {
   // Don't redefine class_exists
}

if (!function_exists('is_admin')) {
    function is_admin() {
        return false;
    }
}

if (!function_exists('apply_filters')) {
    function apply_filters($tag, $value) {
        return \WP_Mock::onFilter($tag)->apply(array_slice(func_get_args(), 1));
    }
}

if (!function_exists('do_action')) {
    function do_action($tag, ...$args) {
         return \WP_Mock::onAction($tag)->react($args);
    }
}

if (!function_exists('esc_html__')) {
    function esc_html__($text, $domain = 'default') {
        return $text;
    }
}
if (!function_exists('esc_html_e')) {
    function esc_html_e($text, $domain = 'default') {
        echo $text;
    }
}
if (!function_exists('esc_attr__')) {
    function esc_attr__($text, $domain = 'default') {
        return $text;
    }
}
if (!function_exists('esc_attr_e')) {
    function esc_attr_e($text, $domain = 'default') {
        echo $text;
    }
}
if (!function_exists('__')) {
    function __($text, $domain = 'default') {
        return $text;
    }
}
if (!function_exists('_e')) {
    function _e($text, $domain = 'default') {
        echo $text;
    }
}
if (!function_exists('_x')) {
    function _x($text, $context, $domain = 'default') {
        return $text;
    }
}
if (!function_exists('esc_url')) {
    function esc_url($url) {
        return $url;
    }
}
if (!function_exists('esc_attr')) {
    function esc_attr($text) {
        return $text;
    }
}
if (!function_exists('esc_html')) {
    function esc_html($text) {
        return $text;
    }
}
if (!function_exists('wp_json_encode')) {
    function wp_json_encode($data, $options = 0, $depth = 512) {
        return json_encode($data, $options, $depth);
    }
}
if (!function_exists('get_post')) {
    function get_post($post = null, $output = OBJECT, $filter = 'raw') {
        return new \WP_Post(new \stdClass());
    }
}

if (!function_exists('get_locale')) {
    function get_locale() {
        return 'en_US';
    }
}
if (!function_exists('load_plugin_textdomain')) {
    function load_plugin_textdomain($domain, $deprecated = false, $plugin_rel_path = false) {
        return true;
    }
}
if (!function_exists('wp_enqueue_script')) {
    function wp_enqueue_script($handle, $src = '', $deps = array(), $ver = false, $in_footer = false) {
        return true;
    }
}
if (!function_exists('wp_localize_script')) {
    function wp_localize_script($handle, $name, $data) {
        return true;
    }
}
if (!function_exists('wp_enqueue_style')) {
    function wp_enqueue_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all') {
        return true;
    }
}
if (!function_exists('is_string')) {
    // defined by PHP
}
if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($str) {
        if (is_string($str)) {
             return trim($str);
        }
        return '';
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
if (!function_exists('register_post_type')) {
    function register_post_type($post_type, $args = array()) {
        return true;
    }
}
if (!function_exists('register_taxonomy')) {
    function register_taxonomy($taxonomy, $object_type, $args = array()) {
        return true;
    }
}
if (!function_exists('wp_send_json_success')) {
    function wp_send_json_success($data = null, $status_code = null) {
        return true;
    }
}
if (!function_exists('wp_send_json_error')) {
    function wp_send_json_error($data = null, $status_code = null) {
        return true;
    }
}
