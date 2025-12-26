<?php
/**
 * Taxonomy Fields Renderer
 *
 * Handles rendering of custom fields in taxonomy forms.
 *
 * @package Dynamic_Online_Services
 * @subpackage Taxonomy_Fields
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Taxonomies\Taxonomy;

use TechmireSolutions\DynamicOnlineServices\Cpt\Sanitization;
use WP_Term;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fields Renderer Class
 */
class FieldsRenderer {

	/**
	 * Initialize taxonomy custom fields hooks with dynamic taxonomy slug.
	 *
	 * @since 1.2.0
	 */
	public static function init(): void {
		$settings      = Sanitization::sanitize_cpt_settings();
		$taxonomy_slug = isset( $settings['taxonomy_slug'] ) ? $settings['taxonomy_slug'] : 'services_category';

		add_action( $taxonomy_slug . '_add_form_fields', array( __CLASS__, 'add' ), 10, 1 );
		add_action( $taxonomy_slug . '_edit_form_fields', array( __CLASS__, 'edit' ), 10, 1 );
	}

	/**
	 * Add custom fields to the taxonomy add form.
	 *
	 * @since 1.1.0
	 * @param string $taxonomy Taxonomy slug.
	 */
	public static function add( $taxonomy ): void {
		wp_nonce_field( 'dynos_service_category_fields', 'dynos_service_category_fields_nonce' );
		?>
		<div class="form-field term-thumbnail-wrap">
			<label for="service-cat-thumbnail"><?php esc_html_e( 'Thumbnail', 'dynamic-online-services' ); ?></label>
			<div id="service-cat-thumbnail-preview" style="max-width: 200px; max-height: 200px; margin: 10px 0;"></div>
			<input type="hidden" name="service_cat_thumbnail" id="service-cat-thumbnail" value="">
			<button type="button" class="button"
				id="upload-thumbnail-button"><?php esc_html_e( 'Upload Image', 'dynamic-online-services' ); ?></button>
			<button type="button" class="button" id="remove-thumbnail-button"
				style="display:none;"><?php esc_html_e( 'Remove Image', 'dynamic-online-services' ); ?></button>
			<p class="description">
				<?php esc_html_e( 'Upload a thumbnail for the category. This will be used as its card image.', 'dynamic-online-services' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Add custom fields to the taxonomy edit form.
	 *
	 * @since 1.1.0
	 * @param WP_Term $term Term object.
	 */
	public static function edit( $term ): void {
		wp_nonce_field( 'dynos_service_category_fields', 'dynos_service_category_fields_nonce' );

		$term_id      = $term->term_id;
		$thumbnail_id = get_term_meta( $term_id, 'service_cat_thumbnail', true );
		$thumbnail    = '';
		if ( $thumbnail_id ) {
			$thumbnail_url = wp_get_attachment_image_url( $thumbnail_id, 'thumbnail' );
			if ( $thumbnail_url ) {
				$thumbnail = $thumbnail_url;
			}
		}
		?>
		<tr class="form-field term-thumbnail-wrap">
			<th scope="row">
				<label for="service-cat-thumbnail"><?php esc_html_e( 'Thumbnail', 'dynamic-online-services' ); ?></label>
			</th>
			<td>
				<div id="service-cat-thumbnail-preview" style="margin: 10px 0;">
					<?php if ( $thumbnail ) : ?>
						<img src="<?php echo esc_url( $thumbnail ); ?>"
							style="max-width: 200px; max-height: 200px; display: block;" />
					<?php else : ?>
						<img id="service-cat-thumbnail-preview-img" src=""
							style="display:none; max-width: 200px; max-height: 200px;" />
					<?php endif; ?>
				</div>
				<input type="hidden" name="service_cat_thumbnail" id="service-cat-thumbnail"
					value="<?php echo esc_attr( $thumbnail_id ); ?>">
				<button type="button" class="button"
					id="upload-thumbnail-button"><?php esc_html_e( 'Upload Image', 'dynamic-online-services' ); ?></button>
				<button type="button" class="button" id="remove-thumbnail-button" <?php if ( empty( $thumbnail_id ) ) : ?>style="display:none;" <?php endif; ?>><?php esc_html_e( 'Remove Image', 'dynamic-online-services' ); ?></button>
				<p class="description">
					<?php esc_html_e( 'Upload a thumbnail for the category. This will be used as its card image.', 'dynamic-online-services' ); ?>
				</p>
			</td>
		</tr>
		<?php
	}
}
