/**
 * Webpack Configuration for Dynamic Online Services Plugin.
 *
 * Extends the default WordPress scripts webpack configuration to include
 * custom entry points for the settings application.
 *
 * @since 1.0.0
 */

const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
	...defaultConfig,
	entry: {
		// Inherit default WordPress scripts entries (blocks, etc.)
		...defaultConfig.entry(),
		// Add custom entry point for settings app
		index: path.resolve(process.cwd(), 'src', 'index.js'),
	},
};
