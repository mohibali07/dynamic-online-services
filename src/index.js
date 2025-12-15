import { render } from '@wordpress/element';
import SettingsApp from './admin/SettingsApp';

import './index.css'; // Assuming we might want some styles

document.addEventListener( 'DOMContentLoaded', () => {
	const container = document.getElementById( 'dynos-settings-root' );
	if ( container ) {
		render( <SettingsApp />, container );
	}
} );
