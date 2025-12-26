<?php
/**
 * WhatsApp Renderer Class
 *
 * Handles the frontend rendering of the floating WhatsApp button.
 *
 * @package Dynamic_Online_Services
 * @subpackage Renderers
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Renderers;

use TechmireSolutions\DynamicOnlineServices\Helpers\Options;
use TechmireSolutions\DynamicOnlineServices\Styles\WhatsappStyles;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WhatsApp Renderer.
 */
class WhatsappRenderer {

	/**
	 * Initialize the renderer.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'wp_footer', array( $this, 'render' ) );
	}

	/**
	 * Render the WhatsApp button.
	 *
	 * @return void
	 */
	public function render(): void {
		$options = Options::get();

		// 1. Check Global Enable.
		if ( ! Options::get_option( $options, 'whatsapp_enabled', false ) ) {
			return;
		}

		// 2. Check Page Visibility.
		$visibility = Options::get_option( $options, 'whatsapp_visibility', 'all' );
		if ( 'home' === $visibility && ! is_front_page() ) {
			return;
		}

		// 3. Check Schedule & Offline Behavior.
		$is_open          = WhatsappStyles::is_open( $options );
		$offline_behavior = Options::get_option( $options, 'whatsapp_offline_behavior', 'hide' );

		if ( ! $is_open && 'hide' === $offline_behavior ) {
			return;
		}

		// 4. Device Visibility.
		$show_desktop = Options::get_option( $options, 'whatsapp_show_desktop', true );
		$show_mobile  = Options::get_option( $options, 'whatsapp_show_mobile', true );
		if ( ! $show_desktop && ! $show_mobile ) {
			return;
		}

		// Data Preparation.
		$number = Options::get_option( $options, 'whatsapp_number', '' );
		$agents_enabled = Options::get_option( $options, 'whatsapp_agents_enabled', false );
		$agents         = Options::get_option( $options, 'whatsapp_agents', array() );

		if ( empty( $number ) && ( ! $agents_enabled || empty( $agents ) ) ) {
			return;
		}

		$message_raw = Options::get_option( $options, 'whatsapp_message', '' );
		$current_url = home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
		$page_title  = get_the_title();
		$message_raw = str_replace( array( '{current_page_url}', '{page_title}' ), array( $current_url, $page_title ), $message_raw );
		$message     = rawurlencode( $message_raw );

		$position   = Options::get_option( $options, 'whatsapp_position', 'right' );
		$bg_color   = Options::get_option( $options, 'whatsapp_bg_color', '#25D366' );
		$icon_color = Options::get_option( $options, 'whatsapp_icon_color', '#FFFFFF' );
		$icon_style = Options::get_option( $options, 'whatsapp_icon_style', 'default' );

		$offset_x = Options::get_option( $options, 'whatsapp_position_offset_x', '20px' );
		$offset_y = Options::get_option( $options, 'whatsapp_position_offset_y', '20px' );

		// URL Construction (Default Single Agent).
		$main_url = 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $number );
		if ( ! empty( $message ) ) {
			$main_url .= '?text=' . $message;
		}

		// Multi-Agent Logic Override.
		$is_multi_agent = $agents_enabled && ! empty( $agents ) && is_array( $agents );
		$main_href      = $is_multi_agent ? 'javascript:void(0);' : esc_url( $main_url );
		$main_onclick   = $is_multi_agent ? 'dynosToggleAgentModal(event)' : 'dynosWhatsAppClick(this, event)';

		// Styling.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$style_attr = sprintf(
			'--whatsapp-bg: %s; --whatsapp-icon: %s; inset-block-end: %s; %s: %s;',
			esc_attr( $bg_color ),
			esc_attr( $icon_color ),
			esc_attr( $offset_y ),
			'left' === $position ? 'inset-inline-start' : 'inset-inline-end',
			esc_attr( $offset_x )
		);

		$classes = array( 'dynos-whatsapp-button' );
		if ( ! $show_desktop ) {
			$classes[] = 'dynos-hide-desktop';
		}
		if ( ! $show_mobile ) {
			$classes[] = 'dynos-hide-mobile';
		}
		if ( ! $is_open ) {
			$classes[] = 'dynos-offline';
		}

		$class_str = implode( ' ', $classes );

		// Analytics.
		$analytics_enabled = Options::get_option( $options, 'whatsapp_analytics_enabled', false );
		$data_analytics    = $analytics_enabled ? 'true' : 'false';

		// CTA & Offline Message Logic.
		$cta_enabled = Options::get_option( $options, 'whatsapp_cta_enabled', false );
		$cta_text    = Options::get_option( $options, 'whatsapp_cta_text', '' );
		$cta_delay   = (int) Options::get_option( $options, 'whatsapp_cta_delay', 5 );
		$is_draggable = Options::get_option( $options, 'whatsapp_draggable', false );

		// If offline and behavior is 'show', override CTA with offline message.
		if ( ! $is_open && 'show' === $offline_behavior ) {
			$cta_enabled = true; // Force enable bubble.
			$cta_delay   = 0;    // Show immediately.
			$cta_text    = Options::get_option( $options, 'whatsapp_offline_text', 'We are currently offline.' );
		}

		$cta_html = '';
		if ( $cta_enabled && ! empty( $cta_text ) ) {
			$cta_html = sprintf(
				'<div class="dynos-whatsapp-cta" style="display:none;" data-delay="%d">%s<span class="dynos-cta-close" onclick="this.parentElement.style.display=\'none\'; event.stopPropagation();">&times;</span></div>',
				esc_attr( $cta_delay ),
				esc_html( $cta_text )
			);
		}

		?>
		<div class="dynos-whatsapp-wrapper" data-draggable="<?php echo $is_draggable ? 'true' : 'false'; ?>" style="<?php echo esc_attr( $style_attr ); ?>; position: fixed; z-index: 9999;">
			<?php
			echo wp_kses(
				$cta_html,
				array(
					'div'  => array(
						'class'      => array(),
						'style'      => array(),
						'data-delay' => array(),
					),
					'span' => array(
						'class'   => array(),
						'onclick' => array(),
					),
				)
			);
			?>
			<a
				href="<?php echo esc_url( $main_href ); ?>"
				class="<?php echo esc_attr( $class_str ); ?>"
				style="position: relative; bottom: 0; <?php echo ( 'right' === $position ? 'right: 0;' : 'left: 0;' ); ?>"
				target="_blank"
				rel="noopener noreferrer"
				aria-label="<?php esc_attr_e( 'Chat on WhatsApp', 'dynamic-online-services' ); ?>"
				data-analytics="<?php echo esc_attr( $data_analytics ); ?>"
				onclick="<?php echo esc_attr( $main_onclick ); ?>"
			>
				<div class="dynos-whatsapp-icon-container">
					<?php if ( 'avatar' === $icon_style ) : ?>
						<svg class="dynos-whatsapp-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"/></svg>
					<?php elseif ( 'chat' === $icon_style ) : ?>
						<svg class="dynos-whatsapp-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 32C114.6 32 0 125.1 0 240c0 49.6 21.4 95 57 130.7C44.5 421.1 2.7 466 2.2 466.5c-2.2 2.3-2.8 5.7-1.5 8.7S4.8 480 8 480c66.3 0 116-31.8 140.6-51.4 32.7 12.3 69 19.4 107.4 19.4 141.4 0 256-93.1 256-208S397.4 32 256 32z"/></svg>
					<?php else : ?>
						<svg class="dynos-whatsapp-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
					<?php endif; ?>
				</div>
			</a>
		</div>

		<?php
		if ( $is_multi_agent ) {
			$args = array(
				'agents'         => $agents,
				'message'        => $message,
				'data_analytics' => $data_analytics,
			);
			include DYNOS_PLUGIN_DIR . 'includes/partials/whatsapp-modal.php';
		}
	}
}
