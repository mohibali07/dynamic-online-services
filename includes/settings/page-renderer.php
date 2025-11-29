<?php
/**
 * Settings Page Renderer
 *
 * Handles rendering of the settings page and additional sections.
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render settings page HTML.
 *
 * @since 1.1.0
 */
function doc_settings_page_html(): void
{
    \DynamicOnlineServices\Settings\PageRenderer::render();
}

/**
 * Render flush rewrite rules section.
 *
 * @since 1.1.0
 */
function doc_render_flush_rewrite_section(): void
{
    // Check user capabilities
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
 *
 * @since 1.1.0
 */
function doc_render_uninstall_settings(): void
{
    // Check user capabilities
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
                            <?php esc_html_e('Delete all plugin options when uninstalling', 'dynamic-online-services'); ?>
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
