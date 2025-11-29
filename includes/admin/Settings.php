<?php
/**
 * Admin Settings Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Admin
 */

namespace DynamicOnlineServices\Admin;

use DynamicOnlineServices\Settings\PageRenderer;
use DynamicOnlineServices\Settings\Defaults;
use DynamicOnlineServices\Settings\Sanitization;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Settings class.
 */
class Settings
{

    /**
     * Instance.
     *
     * @var Settings
     */
    private static $instance;

    /**
     * Get instance.
     *
     * @return Settings
     */
    public static function get_instance(): Settings
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
        add_action('admin_menu', array($this, 'add_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Add admin menu.
     */
    public function add_menu(): void
    {
        add_options_page(
            __('Dynamic Online Services Settings', 'dynamic-online-services'),
            __('Dynamic Services', 'dynamic-online-services'),
            'manage_options',
            'dynamic-online-services',
            array(PageRenderer::class, 'render')
        );
    }

    /**
     * Register settings.
     */
    public function register_settings(): void
    {
        // Register setting with sanitization callback
        register_setting(
            'Dynamic_Online_Services',
            'doc_options',
            array(
                'type' => 'array',
                'sanitize_callback' => array(Sanitization::class, 'sanitize'),
                'default' => Defaults::get_options(),
            )
        );

        // Include legacy settings registration files if needed
        // For now, we rely on the global functions they define, but we should eventually refactor them.
        // We need to make sure they are loaded.
        // They are currently loaded in Plugin::load_dependencies via settings-registration.php
        // But we are replacing settings-registration.php logic here.

        // We need to manually include the section registration files if they are not autoloaded.
        // They are in includes/settings/ and are procedural.
        require_once DOC_PLUGIN_DIR . 'includes/settings/hero-settings.php';
        require_once DOC_PLUGIN_DIR . 'includes/settings/cards-settings.php';
        require_once DOC_PLUGIN_DIR . 'includes/settings/faq-settings.php';
        require_once DOC_PLUGIN_DIR . 'includes/settings/post-type-settings.php';

        // Hero Section Settings
        add_settings_section(
            'doc_hero_section',
            __('Hero Section Settings', 'dynamic-online-services'),
            '__return_null',
            'Dynamic_Online_Services'
        );

        if (function_exists('doc_register_hero_settings')) {
            doc_register_hero_settings();
        }

        // Service Cards Settings
        add_settings_section(
            'doc_cards_section',
            _x('Service Cards Settings', 'Settings section title', 'dynamic-online-services'),
            '__return_null',
            'Dynamic_Online_Services'
        );

        if (function_exists('doc_register_cards_settings')) {
            doc_register_cards_settings();
        }

        // FAQ Accordion Settings
        add_settings_section(
            'doc_faq_section',
            __('FAQ Accordion Settings', 'dynamic-online-services'),
            '__return_null',
            'Dynamic_Online_Services'
        );

        if (function_exists('doc_register_faq_settings')) {
            doc_register_faq_settings();
        }

        // Post Type & Taxonomy Settings
        add_settings_section(
            'doc_post_type_section',
            __('Post Type & Taxonomy Settings', 'dynamic-online-services'),
            '__return_null',
            'Dynamic_Online_Services'
        );

        if (function_exists('doc_register_post_type_settings')) {
            doc_register_post_type_settings();
        }
    }
}
