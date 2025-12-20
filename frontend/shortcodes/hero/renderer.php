<?php
/**
 * Hero Shortcode Renderer
 *
 * Handles rendering of hero section HTML.
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render hero section HTML.
 *
 * @since 1.1.0
 * @param array $args {
 *     Hero section arguments.
 *     @type string $image_url        Background image URL.
 *     @type string $height           Hero height.
 *     @type string $title_color      Title text color.
 *     @type string $font_family      Font family.
 *     @type string $title            Hero title.
 *     @type string $description      Hero description (optional).
 *     @type string $data_attribute   Data attribute name (e.g., 'data-term-id' or 'data-post-id').
 *     @type string $data_value       Data attribute value.
 * }
 * @return string HTML output.
 */
function dynos_render_hero_html( $args ): string {
	$defaults = array(
		'image_url'      => '',
		'height'         => '50vh',
		'title_color'    => '#FFFFFF',
		'font_family'    => 'Helvetica',
		'title'          => '',
		'description'    => '',
		'data_attribute' => '',
		'data_value'     => '',
	);

	$args = wp_parse_args( $args, $defaults );

	// Sanitize all inputs
	$image_url      = esc_url( $args['image_url'] );
	$height         = $args['height'];
	$title_color    = $args['title_color'];
	$font_family    = $args['font_family'];
	$title          = esc_html( $args['title'] );
	$description    = ! empty( $args['description'] ) ? wp_kses_post( $args['description'] ) : '';
	$data_attribute = sanitize_key( $args['data_attribute'] );
	$data_value     = esc_attr( $args['data_value'] );

	// Build style attributes using dedicated style builder helpers
	$hero_inner_style_value   = \TechmireSolutions\DynamicOnlineServices\Helpers\StyleBuilder::build_hero_inner_style( $height, $image_url );
	$hero_content_style_value = \TechmireSolutions\DynamicOnlineServices\Helpers\StyleBuilder::build_hero_content_style( $title_color, $font_family );

	ob_start();
	?>
	<div class="category-hero-container" 
	<?php
	if ( ! empty( $data_attribute ) && ! empty( $data_value ) ) :
		?>
		<?php echo esc_attr( $data_attribute ); ?>="<?php echo esc_attr( $data_value ); ?>" <?php endif; ?>>
		<div class="category-hero-inner" 
		<?php
		if ( ! empty( $hero_inner_style_value ) ) :
			?>
			style="<?php echo esc_attr( $hero_inner_style_value ); ?>" <?php endif; ?>>
			<div class="hero-content" 
			<?php
			if ( ! empty( $hero_content_style_value ) ) :
				?>
				style="<?php echo esc_attr( $hero_content_style_value ); ?>" <?php endif; ?>>
				<h1 class="category-hero-title"><?php echo esc_html( $title ); ?></h1>
				<?php if ( ! empty( $description ) ) : ?>
					<p class="hero-description"><?php echo wp_kses_post( $description ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

