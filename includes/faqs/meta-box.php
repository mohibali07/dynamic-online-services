<?php
/**
 * FAQs Meta Box
 *
 * Handles meta box registration and rendering for FAQs.
 *
 * @package Dynamic_Online_Services
 * @subpackage FAQs
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add a meta box for FAQs to the single service post type.
 *
 * @since 1.1.0
 */
function doc_add_service_faqs_meta_box(): void {
    add_meta_box(
        'doc_service_faqs_meta_box',
        __('FAQs', 'dynamic-online-services'),
        'doc_render_service_faqs_meta_box',
        'service',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'doc_add_service_faqs_meta_box');

/**
 * Render the HTML for the FAQs meta box.
 *
 * @since 1.1.0
 * @param WP_Post $post Post object.
 */
function doc_render_service_faqs_meta_box($post): void {
    wp_nonce_field('doc_save_service_faqs', 'doc_service_faqs_nonce');

    $faqs = get_post_meta($post->ID, 'service_faqs', true);
    if (!is_array($faqs)) {
        $faqs = array();
    }
    
    // Validate FAQ structure
    $faqs = array_filter($faqs, function($faq) {
        return is_array($faq) && isset($faq['question']) && isset($faq['answer']);
    });

    ?>
    <div id="faqs-container">
        <?php foreach ($faqs as $index => $faq) : ?>
            <?php
            // Ensure array structure exists
            if (!is_array($faq)) {
                continue;
            }
            $question = isset($faq['question']) ? $faq['question'] : '';
            $answer   = isset($faq['answer']) ? $faq['answer'] : '';
            ?>
            <div class="faq-item" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                <label><?php esc_html_e('Question:', 'dynamic-online-services'); ?></label>
                <input type="text" name="faqs_question[]" value="<?php echo esc_attr($question); ?>" style="width: 100%; margin-bottom: 5px;" />
                <label><?php esc_html_e('Answer:', 'dynamic-online-services'); ?></label>
                <textarea name="faqs_answer[]" style="width: 100%; min-height: 80px;"><?php echo esc_textarea($answer); ?></textarea>
                <button type="button" class="button remove-faq" style="margin-top: 5px;"><?php esc_html_e('Remove FAQ', 'dynamic-online-services'); ?></button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button button-primary" id="add-faq"><?php esc_html_e('Add New FAQ', 'dynamic-online-services'); ?></button>
    <?php
}

