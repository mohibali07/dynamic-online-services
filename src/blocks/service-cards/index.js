/**
 * Service Cards Block Registration.
 *
 * Registers the service-cards Gutenberg block using metadata from block.json.
 * Uses server-side rendering (save returns null).
 *
 * @since 1.0.0
 */

import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import metadata from './block.json';

// Register the block with WordPress
registerBlockType(metadata.name, {
	edit: Edit,
	save: () => null, // Server-side rendered
});
// Server-side rendered
