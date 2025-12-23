<?php
/**
 * WhatsApp Agent Modal Partial
 *
 * @package Dynamic_Online_Services
 * @subpackage Partials
 * @var array $args ['agents' => array, 'message' => string, 'data_analytics' => string]
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$agents         = isset( $args['agents'] ) ? $args['agents'] : array();
$message        = isset( $args['message'] ) ? $args['message'] : '';
$data_analytics = isset( $args['data_analytics'] ) ? $args['data_analytics'] : 'false';

if ( empty( $agents ) ) {
	return;
}
?>
<div id="dynos-agent-modal" class="dynos-agent-modal-overlay">
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
