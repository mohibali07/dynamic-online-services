<?php
/**
 * FAQs Meta Box
 *
 * Handles meta box registration and rendering for FAQs.
 *
 * @package Dynamic_Online_Services
 * @subpackage FAQs
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\FAQs;

use TechmireSolutions\DynamicOnlineServices\PostTypes\Sanitization;

use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * FAQs Meta Box Class
 */
class MetaBox {

	/**
	 * Initialize the meta box.
	 */
	public static function init(): void {
		add_action( 'add_meta_boxes', array( __CLASS__, 'add' ) );
	}

	/**
	 * Add a meta box for FAQs to the single service post type.
	 *
	 * @since 1.1.0
	 */
	public static function add(): void {
		$settings = Sanitization::sanitize_cpt_settings();
		$service_slug = isset( $settings['service_slug'] ) ? $settings['service_slug'] : 'service';

		add_meta_box(
			'dynos_service_faqs_meta_box',
			__( 'FAQs', 'dynamic-online-services' ),
			array( __CLASS__, 'render' ),
			$service_slug,
			'normal',
			'high'
		);
	}

	/**
	 * Render the HTML for the FAQs meta box.
	 *
	 * @since 1.1.0
	 * @param WP_Post $post Post object.
	 */
	public static function render( $post ): void {
		wp_nonce_field( 'dynos_save_service_faqs', 'dynos_service_faqs_nonce' );

		$faqs = get_post_meta( $post->ID, 'service_faqs', true );
		if ( ! is_array( $faqs ) ) {
			$faqs = array();
		}

		// Validate FAQ structure
		$faqs = array_filter(
			$faqs,
			function ( $faq ) {
				return is_array( $faq ) && isset( $faq['question'] ) && isset( $faq['answer'] );
			}
		);

		?>
		?>
		<div id="faqs-container">
			<?php foreach ($faqs as $index => $faq): ?>
				<?php
				// Ensure array structure exists
				if (!is_array($faq)) {
					continue;
				}
				$question = isset($faq['question']) ? $faq['question'] : '';
				$answer = isset($faq['answer']) ? $faq['answer'] : '';
				?>
				<div class="dynos-faq-item">
					<label><?php esc_html_e('Question:', 'dynamic-online-services'); ?></label>
					<input type="text" name="faqs_question[]" value="<?php echo esc_attr($question); ?>"
						class="regular-text" />
					<label><?php esc_html_e('Answer:', 'dynamic-online-services'); ?></label>
					<textarea name="faqs_answer[]" rows="5"><?php echo esc_textarea($answer); ?></textarea>
					<button type="button" class="button remove-faq"><?php esc_html_e('Remove FAQ', 'dynamic-online-services'); ?></button>
				</div>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button button-primary"
			id="add-faq"><?php esc_html_e('Add New FAQ', 'dynamic-online-services'); ?></button>
		<?php
	}
}

