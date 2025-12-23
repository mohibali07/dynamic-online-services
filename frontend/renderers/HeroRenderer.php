<?php
/**
 * Hero Renderer Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Renderers
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Renderers;

use TechmireSolutions\DynamicOnlineServices\Interfaces\RendererInterface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class HeroRenderer
 */
class HeroRenderer implements RendererInterface {


	/**
	 * Render the hero section.
	 *
	 * @param array $data Data for rendering.
	 * @return string HTML output.
	 */
	public function render( array $data ): string {
		$defaults = array(
			'image_url'          => '',
			'height'             => '50vh',
			'title_color'        => '#FFFFFF',
			'font_family'        => 'sans-serif',
			'title'              => '',
			'description'        => '',
			'data_attribute'     => '',
			'data_value'         => '',
			'breadcrumbs'        => '',
			'text_alignment'     => 'center',
			'vertical_alignment' => 'center',
		);

		$data = wp_parse_args( $data, $defaults );

		// Sanitize all inputs
		$image_url          = esc_url( $data['image_url'] );
		$height             = $data['height'];
		$title_color        = $data['title_color'];
		$font_family        = $data['font_family'];
		$title              = esc_html( $data['title'] );
		$description        = ! empty( $data['description'] ) ? wp_kses_post( $data['description'] ) : '';
		$data_attribute     = sanitize_key( $data['data_attribute'] );
		$data_value         = esc_attr( $data['data_value'] );
		$text_alignment     = esc_attr( $data['text_alignment'] );
		$vertical_alignment = esc_attr( $data['vertical_alignment'] );

		// Build dynamic CSS classes
		$hero_inner_classes   = 'category-hero-inner category-hero-inner--' . $vertical_alignment;
		$hero_content_classes = 'hero-content hero-content--' . $text_alignment;

		// Build style attributes using StyleBuilder class
		$hero_inner_style_value   = \TechmireSolutions\DynamicOnlineServices\Helpers\StyleBuilder::build_hero_inner_style( $height, $image_url );
		$hero_content_style_value = \TechmireSolutions\DynamicOnlineServices\Helpers\StyleBuilder::build_hero_content_style( $title_color, $font_family );

		ob_start();
		?>
		<div class="category-hero-container" role="banner" 
		<?php
		if ( ! empty( $data_attribute ) && ! empty( $data_value ) ) :
			?>
			<?php echo esc_attr( $data_attribute ); ?>="<?php echo esc_attr( $data_value ); ?>" <?php endif; ?> aria-label="<?php echo esc_attr( $title . ' hero section' ); ?>">
			<div class="<?php echo esc_attr( $hero_inner_classes ); ?>" 
			<?php
			if ( ! empty( $hero_inner_style_value ) ) :
				?>
				style="<?php echo esc_attr( $hero_inner_style_value ); ?>" <?php endif; ?>>
				<div class="<?php echo esc_attr( $hero_content_classes ); ?>" 
				<?php
				if ( ! empty( $hero_content_style_value ) ) :
					?>
					style="<?php echo esc_attr( $hero_content_style_value ); ?>" <?php endif; ?>>
					<?php if ( ! empty( $data['breadcrumbs'] ) ) : ?>
						<div class="dynos-breadcrumbs">
							<?php echo wp_kses_post( $data['breadcrumbs'] ); // Safe HTML allowed ?>
						</div>
					<?php endif; ?>
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
}
