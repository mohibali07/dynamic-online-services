<?php
/**
 * Settings Field Renderers
 *
 * Handles rendering of settings field types.
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render color picker field callback.
 *
 * @since 1.1.0
 * @param array $args Field arguments.
 */
function doc_color_field_callback($args): void
{
    \DynamicOnlineServices\Settings\FieldRenderers::color_field_callback($args);
}

/**
 * Render font family field callback.
 *
 * @since 1.1.0
 * @param array $args Field arguments.
 */
function doc_font_family_field_callback($args): void
{
    \DynamicOnlineServices\Settings\FieldRenderers::font_family_field_callback($args);
}

/**
 * Render number field callback.
 *
 * @since 1.1.0
 * @param array $args Field arguments.
 */
function doc_number_field_callback($args): void
{
    \DynamicOnlineServices\Settings\FieldRenderers::number_field_callback($args);
}

/**
 * Render text field callback for CSS dimensions.
 *
 * @since 1.1.0
 * @param array $args Field arguments.
 */
function doc_text_field_callback($args): void
{
    \DynamicOnlineServices\Settings\FieldRenderers::text_field_callback($args);
}

