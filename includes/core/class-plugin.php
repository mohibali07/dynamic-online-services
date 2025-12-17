<?php
/**
 * Main Plugin Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin class.
 */
class Plugin
{

    /**
     * Instance.
     *
     * @var Plugin
     */
    private static $instance;

    /**
     * Get instance.
     *
     * @return Plugin
     */
    public static function get_instance(): Plugin
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct()
    {
        $this->load_dependencies();
        $this->define_hooks();
    }

    /**
     * Load dependencies.
     *
     * @since 1.1.3 Refactored to use DependencyLoader.
     */
    private function load_dependencies(): void
    {
        DependencyLoader::load();
    }

    /**
     * Initialize plugin components.
     *
     * Hooked to 'init' to ensure translations are loaded first.
     *
     * @since 1.1.3 Refactored to use ComponentRegistry.
     * @return void
     */
    public function init_components(): void
    {
        try {
            ComponentRegistry::init();
        } catch (\Exception $e) {
            // Log error in debug mode
            if (defined('WP_DEBUG') && WP_DEBUG) {
                // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
                error_log(
                    sprintf(
                        'DYNOS Component Initialization Failed: %s in %s:%d',
                        $e->getMessage(),
                        $e->getFile(),
                        $e->getLine()
                    )
                );
            }

            // Set transient for admin notice
            set_transient(
                'dynos_component_init_error',
                sprintf(
                    /* translators: %s: error message */
                    __('Dynamic Online Services failed to initialize properly: %s', 'dynamic-online-services'),
                    $e->getMessage()
                ),
                30
            );

            // Prevent partial initialization
            return;
        }
    }

    /**
     * Define hooks.
     */
    private function define_hooks(): void
    {
        // Activation/Deactivation hooks are handled in main file

        // Admin notices
        add_action('admin_notices', [$this, 'display_activation_error_notice']);

        // Register Blocks
        add_action('init', [$this, 'register_blocks']);

        // Initialize components
        add_action('init', [$this, 'init_components'], 5);
    }

    /**
     * Register blocks.
     */
    public function register_blocks(): void
    {
        try {
            register_block_type(DYNOS_PLUGIN_DIR . 'build/blocks/service-cards');
        } catch (\Exception $e) {
            // Log error in debug mode
            if (defined('WP_DEBUG') && WP_DEBUG) {
                // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
                error_log(
                    sprintf(
                        'DYNOS Block Registration Failed: %s',
                        $e->getMessage()
                    )
                );
            }
            // Blocks are optional, continue without them
        }
    }

    /**
     * Display activation error notice.
     */
    public function display_activation_error_notice(): void
    {
        $error_message = get_transient('dynos_activation_error');
        if ($error_message) {
            delete_transient('dynos_activation_error');
            if (class_exists('\\TechmireSolutions\\DynamicOnlineServices\\Helpers\\AdminNotices')) {
                \TechmireSolutions\DynamicOnlineServices\Helpers\AdminNotices::error($error_message);
            } else {
                printf(
                    '<div class="notice notice-error is-dismissible"><p>%s</p></div>',
                    wp_kses_post($error_message)
                );
            }
        }

        // Display component initialization errors
        $component_error = get_transient('dynos_component_init_error');
        if ($component_error) {
            delete_transient('dynos_component_init_error');
            printf(
                '<div class="notice notice-error is-dismissible"><p>%s</p></div>',
                wp_kses_post($component_error)
            );
        }
    }
}
