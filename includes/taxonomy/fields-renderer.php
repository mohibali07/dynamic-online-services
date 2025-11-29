<?php
/**
 * Taxonomy Fields Renderer
 *
 * Handles rendering of custom fields in taxonomy forms.
 *
 * @package Dynamic_Online_Services
 * @subpackage Taxonomy_Fields
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom fields to the taxonomy add form.
 *
 * @since 1.1.0
 * @param string $taxonomy Taxonomy slug.
 */
function doc_add_service_category_custom_fields($taxonomy): void {
    wp_nonce_field('doc_service_category_fields', 'doc_service_category_fields_nonce');
    ?>
    <div class="form-field term-thumbnail-wrap">
        <label for="service-cat-thumbnail"><?php esc_html_e('Thumbnail', 'dynamic-online-services'); ?></label>
        <div id="service-cat-thumbnail-preview" style="max-width: 200px; max-height: 200px; margin: 10px 0;"></div>
        <input type="hidden" name="service_cat_thumbnail" id="service-cat-thumbnail" value="">
        <button type="button" class="button" id="upload-thumbnail-button"><?php esc_html_e('Upload Image', 'dynamic-online-services'); ?></button>
        <button type="button" class="button" id="remove-thumbnail-button" style="display:none;"><?php esc_html_e('Remove Image', 'dynamic-online-services'); ?></button>
        <p class="description"><?php esc_html_e('Upload a thumbnail for the category. This will be used as its card image.', 'dynamic-online-services'); ?></p>
    </div>
    <?php
}
add_action('services_category_add_form_fields', 'doc_add_service_category_custom_fields', 10, 1);

/**
 * Add custom fields to the taxonomy edit form.
 *
 * @since 1.1.0
 * @param WP_Term $term Term object.
 */
function doc_edit_service_category_custom_fields($term): void {
    wp_nonce_field('doc_service_category_fields', 'doc_service_category_fields_nonce');

    $term_id      = $term->term_id;
    $thumbnail_id = get_term_meta($term_id, 'service_cat_thumbnail', true);
    $thumbnail    = '';
    if ($thumbnail_id) {
        $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'thumbnail');
        if ($thumbnail_url) {
            $thumbnail = $thumbnail_url;
        }
    }
    ?>
    <tr class="form-field term-thumbnail-wrap">
        <th scope="row">
            <label for="service-cat-thumbnail"><?php esc_html_e('Thumbnail', 'dynamic-online-services'); ?></label>
        </th>
        <td>
            <div id="service-cat-thumbnail-preview" style="margin: 10px 0;">
                <?php if ($thumbnail) : ?>
                    <img src="<?php echo esc_url($thumbnail); ?>" style="max-width: 200px; max-height: 200px; display: block;" />
                <?php else : ?>
                    <img id="service-cat-thumbnail-preview-img" src="" style="display:none; max-width: 200px; max-height: 200px;" />
                <?php endif; ?>
            </div>
            <input type="hidden" name="service_cat_thumbnail" id="service-cat-thumbnail" value="<?php echo esc_attr($thumbnail_id); ?>">
            <button type="button" class="button" id="upload-thumbnail-button"><?php esc_html_e('Upload Image', 'dynamic-online-services'); ?></button>
            <button type="button" class="button" id="remove-thumbnail-button" <?php echo empty($thumbnail_id) ? 'style="display:none;"' : ''; ?>><?php esc_html_e('Remove Image', 'dynamic-online-services'); ?></button>
            <p class="description"><?php esc_html_e('Upload a thumbnail for the category. This will be used as its card image.', 'dynamic-online-services'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('services_category_edit_form_fields', 'doc_edit_service_category_custom_fields', 10, 1);

