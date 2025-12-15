<?php
/**
 * Render callback for the Service Cards block.
 *
 * @package Dynamic_Online_Services
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// $attributes is available here.

// Map block attributes to shortcode attributes.
$dynos_default_columns = defined('DYNOS_MIN_GRID_COLUMNS') ? (string) DYNOS_MIN_GRID_COLUMNS : '1';
$dynos_shortcode_atts = array(
    'ids'             => $attributes['ids'] ?? '',
    'category'        => $attributes['category'] ?? '',
    'taxonomy'        => $attributes['taxonomy'] ?? 'service-category',
    'orderby'         => $attributes['orderby'] ?? 'date',
    'order'           => $attributes['order'] ?? 'DESC',
    'limit'           => $attributes['limit'] ?? '-1',
    'columns'         => $attributes['columns'] ?? $dynos_default_columns,
    'min_width'       => $attributes['minWidth'] ?? '',
    'show_pagination' => ($attributes['showPagination'] ?? false) ? 'true' : 'false',
);


// We should use the Cards class to render.
if (class_exists('\TechmireSolutions\DynamicOnlineServices\Shortcodes\Cards')) {
    // Instantiate the Cards class and call render.
    $dynos_cards_instance = new \TechmireSolutions\DynamicOnlineServices\Shortcodes\Cards();
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo $dynos_cards_instance->render($dynos_shortcode_atts);
} else {
    // Fallback if class not found (shouldn't happen if plugin is active)
    if (current_user_can('edit_posts')) {
        esc_html_e('Service Cards: Plugin Class not found.', 'dynamic-online-services');
    }
}
