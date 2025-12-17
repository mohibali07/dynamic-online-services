<?php

namespace TechmireSolutions\DynamicOnlineServices\Helpers;

class MockSpy {
    public static $calls = [];
    public static $history = [];

    public static function record($func, $args) {
        if (!isset(self::$calls[$func])) self::$calls[$func] = 0;
        self::$calls[$func]++;
        self::$history[$func][] = $args;
    }

    public static function count($func) {
        return self::$calls[$func] ?? 0;
    }

    public static function get_args($func, $index = 0) {
        return self::$history[$func][$index] ?? null;
    }

    public static function reset() {
        self::$calls = [];
        self::$history = [];
    }
}

function get_option($option, $default = false) {
    MockSpy::record('get_option', func_get_args());
    return \get_option(...func_get_args());
}

function get_transient($transient) {
    MockSpy::record('get_transient', func_get_args());
    return \get_transient(...func_get_args());
}

function get_post_type($post = null) {
    echo "DEBUG: Namespaced get_post_type called\n";
    MockSpy::record('get_post_type', func_get_args());
    return \get_post_type(...func_get_args());
}

function set_transient($transient, $value, $expiration = 0) {
    MockSpy::record('set_transient', func_get_args());
    return \set_transient(...func_get_args());
}

function delete_transient($transient) {
    MockSpy::record('delete_transient', func_get_args());
    return \delete_transient(...func_get_args());
}

function maybe_serialize($data) {
    if ($data instanceof \Closure) {
        return $data;
    }
    if (is_array($data) || is_object($data)) {
        try {
            return serialize($data);
        } catch (\Throwable $e) {
            return $data;
        }
    }
    return $data;
}

function apply_filters($tag, $value) {
    $args = func_get_args();
    return \WP_Mock::onFilter($tag)->apply(array_slice($args, 1));
}

function do_action($tag) {
    $args = func_get_args();
    return \WP_Mock::onAction($tag)->react(array_slice($args, 1));
}

function wp_parse_args($args, $defaults = '') {
    MockSpy::record('wp_parse_args', func_get_args());
    return \wp_parse_args(...func_get_args());
}
