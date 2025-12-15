<?php
/**
 * Settings Field Renderers
 *
 * Handles rendering of settings field types.
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render color picker field callback.
 *
 * @since 1.1.0
 * @param array $args Field arguments.
 */
function dynos_color_field_callback( $args ): void {
	\TechmireSolutions\DynamicOnlineServices\Settings\FieldRenderers::color_field_callback( $args );
}

/**
 * Render font family field callback.
 *
 * @since 1.1.0
 * @param array $args Field arguments.
 */
function dynos_font_family_field_callback( $args ): void {
	\TechmireSolutions\DynamicOnlineServices\Settings\FieldRenderers::font_family_field_callback( $args );
}

/**
 * Render number field callback.
 *
 * @since 1.1.0
 * @param array $args Field arguments.
 */
function dynos_number_field_callback( $args ): void {
	\TechmireSolutions\DynamicOnlineServices\Settings\FieldRenderers::number_field_callback( $args );
}

/**
 * Render text field callback for CSS dimensions.
 *
 * @since 1.1.0
 * @param array $args Field arguments.
 */
function dynos_text_field_callback( $args ): void {
	\TechmireSolutions\DynamicOnlineServices\Settings\FieldRenderers::text_field_callback( $args );
}

/**
 * Render checkbox field callback.
 *
 * @since 1.1.0
 * @param array $args Field arguments.
 */
function dynos_checkbox_field_callback( $args ): void {
	\TechmireSolutions\DynamicOnlineServices\Settings\FieldRenderers::checkbox_field_callback( $args );
}

/**
 * Render radio field callback.
 *
 * @since 1.1.0
 * @param array $args Field arguments with 'options' array.
 */
function dynos_radio_field_callback( $args ): void {
	\TechmireSolutions\DynamicOnlineServices\Settings\FieldRenderers::radio_field_callback( $args );
}
