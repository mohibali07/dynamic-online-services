<?php
/**
 * Settings Page Renderer
 *
 * Handles rendering of the settings page and additional sections.
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render settings page HTML.
 *
 * @since 1.1.0
 */
function dynos_settings_page_html(): void {
	if ( class_exists( '\DynamicOnlineServices\Settings\PageRenderer' ) ) {
		\DynamicOnlineServices\Settings\PageRenderer::render();
	}
}

/**
 * Render flush rewrite rules section.
 *
 * @since 1.1.0
 */
function dynos_render_flush_rewrite_section(): void {
	if ( class_exists( '\DynamicOnlineServices\Settings\PageRenderer' ) ) {
		\DynamicOnlineServices\Settings\PageRenderer::render_flush_rewrite_section();
	}
}

/**
 * Render uninstall settings section.
 *
 * @since 1.1.0
 */
function dynos_render_uninstall_settings(): void {
	if ( class_exists( '\DynamicOnlineServices\Settings\PageRenderer' ) ) {
		\DynamicOnlineServices\Settings\PageRenderer::render_uninstall_settings();
	}
}
