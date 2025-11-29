<?php
/**
 * Main Plugin Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

namespace DynamicOnlineServices\Core;

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
     */
    private function load_dependencies(): void
    {
        require_once DOC_PLUGIN_DIR . 'includes/helpers.php';
        require_once DOC_PLUGIN_DIR . 'includes/post-types.php';
        require_once DOC_PLUGIN_DIR . 'includes/taxonomy-fields.php';
        require_once DOC_PLUGIN_DIR . 'includes/faqs-meta-box.php';
        require_once DOC_PLUGIN_DIR . 'includes/shortcodes-faqs.php';
        require_once DOC_PLUGIN_DIR . 'includes/shortcodes-category.php';
        require_once DOC_PLUGIN_DIR . 'includes/shortcodes-hero.php';
        require_once DOC_PLUGIN_DIR . 'includes/front-end-scripts.php';
        require_once DOC_PLUGIN_DIR . 'includes/admin-scripts.php';

        // Initialize Settings
        \DynamicOnlineServices\Admin\Settings::get_instance();

        // Initialize Shortcodes
        \DynamicOnlineServices\Shortcodes\Category::init();
        \DynamicOnlineServices\Shortcodes\Faqs::init();
        \DynamicOnlineServices\Shortcodes\Hero::init();
    }

    /**
     * Define hooks.
     */
    private function define_hooks(): void
    {
        // Activation/Deactivation hooks are handled in main file

        // Admin notices
        add_action('admin_notices', array($this, 'display_activation_error_notice'));
    }

    /**
     * Display activation error notice.
     */
    public function display_activation_error_notice(): void
    {
        $error_message = get_transient('doc_activation_error');
        if ($error_message) {
            delete_transient('doc_activation_error');
            if (function_exists('doc_admin_error_notice')) {
                doc_admin_error_notice($error_message);
            } else {
                printf(
                    '<div class="notice notice-error is-dismissible"><p>%s</p></div>',
                    wp_kses_post($error_message)
                );
            }
        }
    }
}
