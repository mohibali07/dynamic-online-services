<?php
/**
 * Render callback for the Hero Section block.
 *
 * @package Dynamic_Online_Services
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Retrieve attributes.
$bg_type = isset( $attributes['backgroundType'] ) ? $attributes['backgroundType'] : 'image';
$desktop_image_url = isset( $attributes['desktopImageUrl'] ) ? $attributes['desktopImageUrl'] : '';
$mobile_image_url = isset( $attributes['mobileImageUrl'] ) ? $attributes['mobileImageUrl'] : '';
$video_url = isset( $attributes['videoUrl'] ) ? $attributes['videoUrl'] : '';

// Time-based background logic.
$morning_image = isset( $attributes['morningImageUrl'] ) ? $attributes['morningImageUrl'] : '';
$evening_image = isset( $attributes['eveningImageUrl'] ) ? $attributes['eveningImageUrl'] : '';
$evening_start_hour = isset( $attributes['eveningStartHour'] ) ? (int) $attributes['eveningStartHour'] : 18;

// Determine current hour (server time).
$current_hour = (int) gmdate( 'H' );

// Apply time-based background if configured.
if ( ! empty( $morning_image ) && ! empty( $evening_image ) ) {
	if ( $current_hour >= $evening_start_hour ) {
		$desktop_image_url = $evening_image;
	} else {
		$desktop_image_url = $morning_image;
	}
}

// If mobile image is empty, fallback to desktop.
if ( empty( $mobile_image_url ) ) {
    $mobile_image_url = $desktop_image_url;
}

$title = isset( $attributes['title'] ) ? $attributes['title'] : '';
// Dynamic Replacements
$current_user = wp_get_current_user();
$user_name = $current_user->exists() ? $current_user->display_name : 'Guest';
$title = str_replace( '{user_name}', $user_name, $title );
$title = str_replace( '{current_date}', date_i18n( get_option( 'date_format' ) ), $title );

$subtitle = isset( $attributes['subtitle'] ) ? $attributes['subtitle'] : '';
$overlay_color = isset( $attributes['overlayColor'] ) ? $attributes['overlayColor'] : '#000000';
$overlay_opacity = isset( $attributes['overlayOpacity'] ) ? $attributes['overlayOpacity'] : 0.5;
$min_height = isset( $attributes['minHeightDesktop'] ) ? $attributes['minHeightDesktop'] : '600px';

// CSS Variable for Background
$bg_style = '';
$video_element = '';
if ( 'image' === $bg_type && ! empty( $desktop_image_url ) ) {
    $bg_style = sprintf( 'background-image: url(%s);', esc_url( $desktop_image_url ) );
} elseif ( 'video' === $bg_type && ! empty( $video_url ) ) {
	// Video background will be rendered separately as an HTML element.
	$video_element = sprintf(
		'<video class="dynos-hero-video" autoplay loop muted playsinline style="position: absolute; top: 0; left: 0; width: 100%%; height: 100%%; object-fit: cover; z-index: 0;"><source src="%s" type="video/mp4"></video>',
		esc_url( $video_url )
	);
} elseif ( 'gradient' === $bg_type ) {
	// Example gradient - in production, you'd want more attributes for gradient config.
	$bg_style = 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);';
}

// Parallax Class
$parallax_class = ! empty( $attributes['enableParallax'] ) ? 'dynos-parallax-bg' : '';
$kenburns_class = ! empty( $attributes['enableKenBurns'] ) ? 'dynos-ken-burns' : '';

$wrapper_style = sprintf(
    'min-height: %s; %s',
    esc_attr( $min_height ),
    $bg_style
);

// Responsive style injection for mobile image
$unique_id = uniqid( 'dynos-hero-' );
?>
<style>
    #<?php echo esc_attr( $unique_id ); ?> {
        <?php echo $bg_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    }
    @media (max-width: 768px) {
        #<?php echo esc_attr( $unique_id ); ?> {
            background-image: url(<?php echo esc_url( $mobile_image_url ); ?>) !important;
            min-height: <?php echo esc_attr( isset( $attributes['minHeightMobile'] ) ? $attributes['minHeightMobile'] : '400px' ); ?> !important;
        }
    }
</style>

<div
    id="<?php echo esc_attr( $unique_id ); ?>"
    class="<?php echo esc_attr( 'dynos-hero-section wp-block-dynamic-online-services-hero-section ' . $parallax_class . ' ' . $kenburns_class ); ?>"
    style="<?php echo esc_attr( $wrapper_style ); ?>"
>
    <?php
	// Render video background if applicable.
	if ( ! empty( $video_element ) ) {
		echo $video_element; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>
    <div
        class="dynos-hero-overlay"
        style="background-color: <?php echo esc_attr( $overlay_color ); ?>; opacity: <?php echo esc_attr( $overlay_opacity ); ?>;"
    ></div>

    <div class="dynos-hero-content">
        <?php if ( ! empty( $title ) ) : ?>
            <<?php echo esc_attr( $attributes['titleTag'] ); ?> class="dynos-hero-title">
                <?php echo wp_kses_post( $title ); ?>
            </<?php echo esc_attr( $attributes['titleTag'] ); ?>>
        <?php endif; ?>

        <?php if ( ! empty( $subtitle ) ) : ?>
            <span class="dynos-hero-subtitle"><?php echo wp_kses_post( $subtitle ); ?></span>
        <?php endif; ?>

        <div class="dynos-hero-buttons">
            <?php if ( ! empty( $attributes['btnPrimaryText'] ) ) : ?>
                <a href="<?php echo esc_url( $attributes['btnPrimaryUrl'] ); ?>" class="dynos-hero-btn dynos-btn-primary" style="background: #fff; color: #333;">
                    <?php echo esc_html( $attributes['btnPrimaryText'] ); ?>
                </a>
            <?php endif; ?>
             <?php if ( ! empty( $attributes['btnSecondaryText'] ) ) : ?>
                <a href="<?php echo esc_url( $attributes['btnSecondaryUrl'] ); ?>" class="dynos-hero-btn dynos-btn-secondary" style="border: 2px solid #fff; color: #fff;">
                    <?php echo esc_html( $attributes['btnSecondaryText'] ); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <?php
	// Render Shape Divider
	$shape_divider = isset( $attributes['shapeDivider'] ) ? $attributes['shapeDivider'] : 'none';
	if ( 'none' !== $shape_divider ) :
		$svg_content = '';

		if ( 'waves' === $shape_divider ) {
			$svg_content = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#ffffff"></path></svg>';
		} elseif ( 'curve' === $shape_divider ) {
			$svg_content = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="#ffffff"></path><path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="#ffffff"></path><path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="#ffffff"></path></svg>';
		} elseif ( 'triangle' === $shape_divider ) {
			$svg_content = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M1200 0L0 0 598.97 114.72 1200 0z" fill="#ffffff"></path></svg>';
		}

		if ( ! empty( $svg_content ) ) :
	?>
	<div class="dynos-shape-divider" style="position: absolute; bottom: 0; left: 0; width: 100%; overflow: hidden; line-height: 0; z-index: 3;">
		<?php echo $svg_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<?php
		endif;
	endif;
	?>
</div>
