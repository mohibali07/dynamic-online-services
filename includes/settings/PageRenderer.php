<?php
/**
 * Settings Page Renderer Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings
 */

namespace DynamicOnlineServices\Settings;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * PageRenderer class.
 */
class PageRenderer
{

    /**
     * Render settings page HTML.
     *
     * @return void
     */
    public static function render(): void
    {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            if (function_exists('doc_handle_permission_error')) {
                doc_handle_permission_error(
                    esc_html__('You do not have sufficient permissions to access this page.', 'dynamic-online-services'),
                    'manage_options'
                );
            } else {
                wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'dynamic-online-services'));
            }
            return;
        }

        // Display settings updated message
        if (isset($_GET['settings-updated'])) {
            $settings_updated = sanitize_text_field(wp_unslash($_GET['settings-updated']));
            if ('true' === $settings_updated) {
                add_settings_error(
                    'Dynamic_Online_Services',
                    'doc_message',
                    esc_html__('Settings Saved', 'dynamic-online-services'),
                    'updated'
                );
            }
        }

        // Handle uninstall settings form submission
        if (isset($_POST['doc_save_uninstall_settings']) && check_admin_referer('doc_uninstall_settings', 'doc_uninstall_settings_nonce')) {
            $delete_data = isset($_POST['doc_delete_data_on_uninstall']) && '1' === $_POST['doc_delete_data_on_uninstall'];
            update_option('doc_delete_data_on_uninstall', $delete_data);
            add_settings_error(
                'Dynamic_Online_Services',
                'doc_uninstall_message',
                esc_html__('Uninstall settings saved.', 'dynamic-online-services'),
                'updated'
            );
        }

        // Handle flush rewrite rules
        if (isset($_POST['doc_flush_rewrite_rules']) && check_admin_referer('doc_flush_rewrite_rules', 'doc_flush_rewrite_rules_nonce')) {
            flush_rewrite_rules();
            add_settings_error(
                'Dynamic_Online_Services',
                'doc_rewrite_message',
                esc_html__('Rewrite rules flushed successfully. URL slugs are now updated.', 'dynamic-online-services'),
                'updated'
            );
        }

        // Check if slug settings were changed
        $options = get_option('doc_options', Defaults::get_options());

        // Use global helper for slug validation if available
        $current_service_slug = function_exists('doc_validate_slug') ? doc_validate_slug($options['service_post_type_slug'] ?? '', 'services') : ($options['service_post_type_slug'] ?? 'services');
        $current_taxonomy_slug = function_exists('doc_validate_slug') ? doc_validate_slug($options['service_taxonomy_slug'] ?? '', 'service-category') : ($options['service_taxonomy_slug'] ?? 'service-category');

        // Check against previously saved slugs
        $previous_service_slug = get_option('doc_previous_service_slug', $current_service_slug);
        $previous_taxonomy_slug = get_option('doc_previous_taxonomy_slug', $current_taxonomy_slug);

        if (function_exists('doc_validate_slug')) {
            $previous_service_slug = doc_validate_slug($previous_service_slug, $current_service_slug);
            $previous_taxonomy_slug = doc_validate_slug($previous_taxonomy_slug, $current_taxonomy_slug);
        }

        $slugs_changed = ($current_service_slug !== $previous_service_slug || $current_taxonomy_slug !== $previous_taxonomy_slug);

        if ($slugs_changed) {
            update_option('doc_previous_service_slug', $current_service_slug);
            update_option('doc_previous_taxonomy_slug', $current_taxonomy_slug);

            if (isset($_POST['submit']) && check_admin_referer('Dynamic_Online_Services-options')) {
                flush_rewrite_rules(false);
                add_settings_error(
                    'Dynamic_Online_Services',
                    'doc_rewrite_auto_flushed',
                    esc_html__('URL slugs have been changed and rewrite rules have been automatically flushed.', 'dynamic-online-services'),
                    'updated'
                );
            } else {
                add_settings_error(
                    'Dynamic_Online_Services',
                    'doc_slug_change_notice',
                    esc_html__('URL slugs have been changed. Please save settings to automatically flush rewrite rules, or use the manual flush button below.', 'dynamic-online-services'),
                    'warning'
                );
            }
        }

        settings_errors('Dynamic_Online_Services');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="<?php echo esc_url(admin_url('options.php')); ?>" method="post">
                <?php
                settings_fields('Dynamic_Online_Services');
                do_settings_sections('Dynamic_Online_Services');
                submit_button(esc_html__('Save Settings', 'dynamic-online-services'));
                ?>
            </form>
            <?php self::render_flush_rewrite_section(); ?>
            <?php self::render_uninstall_settings(); ?>
        </div>
        <?php
    }

    /**
     * Render flush rewrite rules section.
     */
    private static function render_flush_rewrite_section(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <hr>
        <h2><?php esc_html_e('Rewrite Rules', 'dynamic-online-services'); ?></h2>
        <p class="description">
            <?php esc_html_e('If you have changed the post type or taxonomy slugs, you need to flush rewrite rules for the changes to take effect.', 'dynamic-online-services'); ?>
        </p>
        <form method="post" action="">
            <?php wp_nonce_field('doc_flush_rewrite_rules', 'doc_flush_rewrite_rules_nonce'); ?>
            <p>
                <input type="submit" name="doc_flush_rewrite_rules" class="button button-secondary"
                    value="<?php esc_attr_e('Flush Rewrite Rules', 'dynamic-online-services'); ?>" />
                <span
                    class="description"><?php esc_html_e('Click this button to update the URL structure.', 'dynamic-online-services'); ?></span>
            </p>
        </form>
        <?php
    }

    /**
     * Render uninstall settings section.
     */
    private static function render_uninstall_settings(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $delete_data = get_option('doc_delete_data_on_uninstall', false);
        ?>
        <hr>
        <h2><?php esc_html_e('Uninstall Settings', 'dynamic-online-services'); ?></h2>
        <p class="description">
            <?php esc_html_e('Choose what happens when the plugin is uninstalled.', 'dynamic-online-services'); ?>
        </p>
        <form method="post" action="">
            <?php wp_nonce_field('doc_uninstall_settings', 'doc_uninstall_settings_nonce'); ?>
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="doc_delete_data_on_uninstall">
                                <?php esc_html_e('Delete Data on Uninstall', 'dynamic-online-services'); ?>
                            </label>
                        </th>
                        <td>
                            <label for="doc_delete_data_on_uninstall">
                                <input type="checkbox" name="doc_delete_data_on_uninstall" id="doc_delete_data_on_uninstall"
                                    value="1" <?php checked($delete_data, true); ?>>
                                <?php esc_html_e('Delete all plugin data when uninstalling', 'dynamic-online-services'); ?>
                            </label>
                            <p class="description">
                                <?php esc_html_e('If checked, all plugin options will be deleted when the plugin is uninstalled. Custom post types and taxonomy data will not be deleted.', 'dynamic-online-services'); ?>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php submit_button(esc_html__('Save Uninstall Settings', 'dynamic-online-services'), 'secondary', 'doc_save_uninstall_settings'); ?>
        </form>
        <?php
    }
}
