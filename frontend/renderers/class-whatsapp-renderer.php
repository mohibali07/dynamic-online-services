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
	 * Check if the chat should be displayed based on schedule.
	 *
	 * @param array $options Plugin options.
	 * @return bool
	 */
	private function is_open( array $options ): bool {
		$availability = Options::get_option( $options, 'whatsapp_availability', false );

		// If scheduling is not enabled, it's always available.
		if ( ! $availability ) {
			return true;
		}

		$timezone_string = Options::get_option( $options, 'whatsapp_timezone', 'UTC' );
		try {
			$timezone = new \DateTimeZone( $timezone_string );
		} catch ( \Exception $e ) {
			$timezone = new \DateTimeZone( 'UTC' );
		}

		$current_time = new \DateTime( 'now', $timezone );
		$now          = $current_time->format( 'H:i' );

		$start = Options::get_option( $options, 'whatsapp_schedule_start', '09:00' );
		$end   = Options::get_option( $options, 'whatsapp_schedule_end', '17:00' );

		return $now >= $start && $now <= $end;
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
		$is_open          = $this->is_open( $options );
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
		// If explicit number is missing AND agents are not enabled/empty, return.
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
			'--whatsapp-bg: %s; --whatsapp-icon: %s; bottom: %s; %s: %s;',
			esc_attr( $bg_color ),
			esc_attr( $icon_color ),
			esc_attr( $offset_y ),
			esc_attr( $position ),
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

		// If offline and behavior is 'show', override CTA with offline message.
		if ( ! $is_open && 'show' === $offline_behavior ) {
			$cta_enabled = true; // Force enable bubble.
			$cta_delay   = 0;    // Show immediately.
			$cta_text    = Options::get_option( $options, 'whatsapp_offline_text', 'We are currently offline.' );
			// If multi-agent is on, maybe disable it for offline mode if specific logic needed?
			// For now, let's keep it simple: Offline message acts as CTA. Click opens modal or overrides.
			// If offline, maybe clicking button should show alert if multi-agent?
			// Let's assume offline message tells them to leave a message, implying they click and go to WA.
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
		<style>
			.dynos-whatsapp-button {
				position: fixed;
				width: 60px;
				height: 60px;
				background-color: var(--whatsapp-bg);
				color: var(--whatsapp-icon);
				border-radius: 50%;
				text-align: center;
				font-size: 30px;
				box-shadow: 2px 2px 3px rgba(0,0,0,0.2);
				z-index: 9999;
				display: flex;
				align-items: center;
				justify-content: center;
				text-decoration: none;
				transition: transform 0.3s ease;
			}
			.dynos-whatsapp-button:hover {
				transform: scale(1.1);
			}
			.dynos-offline {
				filter: grayscale(100%);
			}
			.dynos-whatsapp-cta {
				position: absolute;
				bottom: 70px;
				width: 200px;
				background: #fff;
				color: #333;
				padding: 10px;
				border-radius: 8px;
				box-shadow: 0 4px 6px rgba(0,0,0,0.1);
				font-size: 14px;
				line-height: 1.4;
				right: 0;
				animation: dynosFadeIn 0.5s;
			}
			.dynos-position-left .dynos-whatsapp-cta { left: 0; right: auto; }

			.dynos-cta-close {
				position: absolute;
				top: 0px;
				right: 5px;
				font-size: 16px;
				cursor: pointer;
				color: #999;
			}
			.dynos-whatsapp-icon { width: 35px; height: 35px; fill: currentColor; }

			/* Agent Modal Styles */
			.dynos-agent-modal-overlay {
				position: fixed;
				top: 0; left: 0; width: 100%; height: 100%;
				background: rgba(0,0,0,0.5);
				z-index: 10000;
				display: none;
				justify-content: center;
				align-items: center;
			}
			.dynos-agent-modal {
				background: #fff;
				width: 90%;
				max-width: 400px;
				border-radius: 10px;
				padding: 20px;
				box-shadow: 0 5px 15px rgba(0,0,0,0.3);
				position: relative;
				max-height: 80vh;
				overflow-y: auto;
			}
			.dynos-agent-header {
				font-weight: bold;
				font-size: 18px;
				margin-bottom: 15px;
				text-align: center;
				border-bottom: 1px solid #eee;
				padding-bottom: 10px;
                color: #333;
			}
			.dynos-agent-list {
				display: flex;
				flex-direction: column;
				gap: 10px;
			}
			.dynos-agent-item {
				display: flex;
				align-items: center;
				padding: 10px;
				border: 1px solid #eee;
				border-radius: 8px;
				text-decoration: none;
				color: #333;
				transition: background 0.2s;
			}
			.dynos-agent-item:hover {
				background: #f9f9f9;
			}
			.dynos-agent-avatar {
				width: 40px;
				height: 40px;
				border-radius: 50%;
				background: #ddd;
				margin-right: 12px;
				object-fit: cover;
			}
			.dynos-agent-info {
				display: flex;
				flex-direction: column;
			}
			.dynos-agent-name {
				font-weight: bold;
				font-size: 14px;
			}
			.dynos-agent-label {
				font-size: 12px;
				color: #666;
			}
			.dynos-modal-close {
				position: absolute;
				top: 10px;
				right: 15px;
				font-size: 24px;
				cursor: pointer;
				color: #999;
			}

			@keyframes dynosFadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

			@media (min-width: 769px) { .dynos-hide-desktop { display: none !important; } }
			@media (max-width: 768px) { .dynos-hide-mobile { display: none !important; } }
		</style>

		<div class="dynos-whatsapp-wrapper" style="<?php echo esc_attr( $style_attr ); ?>; position: fixed; z-index: 9999;">
			<?php echo $cta_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<a
				href="<?php echo $main_href; ?>"
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

		<?php if ( $is_multi_agent ) : ?>
		<div id="dynos-agent-modal" class="dynos-agent-modal-overlay" onclick="if(event.target === this) this.style.display='none';">
			<div class="dynos-agent-modal">
				<span class="dynos-modal-close" onclick="document.getElementById('dynos-agent-modal').style.display='none';">&times;</span>
				<div class="dynos-agent-header"><?php esc_html_e( 'Choose a Support Agent', 'dynamic-online-services' ); ?></div>
				<div class="dynos-agent-list">
					<?php foreach ( $agents as $agent ) :
						$a_number = isset( $agent['number'] ) ? $agent['number'] : '';
						if ( empty( $a_number ) ) continue;
						$a_url = 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $a_number );
						if ( ! empty( $message ) ) {
							$a_url .= '?text=' . $message;
						}
						$a_name = isset( $agent['name'] ) ? $agent['name'] : 'Support Agent';
						$a_label = isset( $agent['label'] ) ? $agent['label'] : '';
						$a_avatar = isset( $agent['avatar_url'] ) ? $agent['avatar_url'] : '';
					?>
					<a
						href="<?php echo esc_url( $a_url ); ?>"
						class="dynos-agent-item"
						target="_blank"
						onclick="dynosWhatsAppClick(this, event)"
						data-analytics="<?php echo esc_attr( $data_analytics ); ?>"
					>
						<?php if ( ! empty( $a_avatar ) ) : ?>
							<img src="<?php echo esc_url( $a_avatar ); ?>" class="dynos-agent-avatar" alt="<?php echo esc_attr( $a_name ); ?>" />
						<?php else: ?>
							<div class="dynos-agent-avatar" style="background:#eee; display:flex; align-items:center; justify-content:center; color:#888;">
								<svg style="width:24px;height:24px;fill:currentColor" viewBox="0 0 448 512"><path d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z"/></svg>
							</div>
						<?php endif; ?>
						<div class="dynos-agent-info">
							<span class="dynos-agent-name"><?php echo esc_html( $a_name ); ?></span>
							<?php if ( ! empty( $a_label ) ) : ?>
								<span class="dynos-agent-label"><?php echo esc_html( $a_label ); ?></span>
							<?php endif; ?>
						</div>
					</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<script>
		document.addEventListener('DOMContentLoaded', function() {
			var cta = document.querySelector('.dynos-whatsapp-cta');
			if (cta) {
				var delay = parseInt(cta.getAttribute('data-delay'), 10) || 0;
				setTimeout(function() {
					cta.style.display = 'block';
				}, delay * 1000);
			}
			// Exit Intent (Show if not already shown)
			document.addEventListener('mouseleave', function(e) {
				if(e.clientY < 0 && cta && cta.style.display === 'none') {
					cta.style.display = 'block';
				}
			});
		});

		function dynosWhatsAppClick(element, event) {
			if(element.dataset.analytics === 'true') {
				if(typeof gtag === 'function') { gtag('event', 'click', { 'event_category': 'Contact', 'event_label': 'WhatsApp', 'transport_type': 'beacon' }); }
				if(typeof fbq === 'function') { fbq('track', 'Contact'); }
			}
		}

		function dynosToggleAgentModal(event) {
			event.preventDefault();
			var modal = document.getElementById('dynos-agent-modal');
			if(modal) {
				modal.style.display = (modal.style.display === 'none' || modal.style.display === '') ? 'flex' : 'none';
			}
		}
		</script>
		<?php
	}
}
