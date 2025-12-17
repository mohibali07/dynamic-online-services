/**
 * Main Entry Point for Settings Application.
 *
 * Renders the settings React application into the designated DOM container.
 * This file is the webpack entry point for the admin settings interface.
 *
 * @since 1.0.0
 */

import { render } from '@wordpress/element';
import SettingsApp from './admin/SettingsApp';
import './index.css';

/**
 * Initialize the settings app when DOM is ready.
 */
document.addEventListener('DOMContentLoaded', () => {
	// Find the settings container
	const container = document.getElementById('dynos-settings-root');

	// Defensive check: ensure container exists before rendering
	if (container) {
		render(<SettingsApp />, container);
	} else if (typeof window.sosConsoleLog === 'function') {
		window.sosConsoleLog(
			'warn',
			'Settings container #dynos-settings-root not found'
		);
	}
});
