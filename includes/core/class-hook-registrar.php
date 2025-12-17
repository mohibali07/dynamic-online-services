<?php
/**
 * Hook Registrar Class
 *
 * Handles registration of all WordPress hooks for the plugin.
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

use TechmireSolutions\DynamicOnlineServices\Core\Plugin;

if (!defined('ABSPATH')) {
    exit;
}

class HookRegistrar
{
    /**
     * Register all hooks used by the plugin.
     */
    public static function register_hooks(): void
    {
        // Admin notices
        add_action('admin_notices', [Plugin::class, 'display_activation_error_notice']);

        // Register Gutenberg blocks
        add_action('init', [Plugin::class, 'register_blocks']);

        // Initialize components (priority 5 to run before other init hooks)
        add_action('init', [Plugin::class, 'init_components'], 5);
    }
}
?>
