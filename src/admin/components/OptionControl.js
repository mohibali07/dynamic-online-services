import {
	TextControl,
	ToggleControl,
	ColorPalette,
	BaseControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * OptionControl Component.
 *
 * Renders a control for a specific setting key within the dynos_options object.
 *
 * @param {Object}   props
 * @param {string}   props.label     Label for the setting.
 * @param {string}   props.help      Help text/Description.
 * @param {string}   props.type      'text', 'number', 'color', 'toggle'.
 * @param {string}   props.optionKey The key in dynos_options.
 * @param {Object}   props.settings  The full settings object.
 * @param {Function} props.onChange  Callback when value changes.
 */
const OptionControl = ( {
	label,
	help,
	type,
	optionKey,
	settings,
	onChange,
} ) => {
	const value = settings[ optionKey ];

	const handleChange = ( newValue ) => {
		onChange( {
			...settings,
			[ optionKey ]: newValue,
		} );
	};

	switch ( type ) {
		case 'toggle':
		case 'checkbox':
			return (
				<ToggleControl
					label={ label }
					help={ help }
					checked={ !! value }
					onChange={ handleChange }
				/>
			);
		case 'color':
			return (
				<div className="components-base-control">
					<div className="components-base-control__field">
						<label className="components-base-control__label">
							{ label }
						</label>
						<ColorPalette
							colors={
								window.dynosSettings?.colorPresets || [
									{ name: 'Black', color: '#000000' },
									{ name: 'White', color: '#ffffff' },
									{ name: 'Red', color: '#f00' },
									{ name: 'Blue', color: '#00f' },
								]
							}
							value={ value }
							onChange={ handleChange }
						/>
						{ help && (
							<p className="components-base-control__help">
								{ help }
							</p>
						) }
					</div>
				</div>
			);

		case 'number':
			return (
				<TextControl
					label={ label }
					help={ help }
					type="number"
					value={ value }
					onChange={ handleChange }
				/>
			);
		default:
			return (
				<TextControl
					label={ label }
					help={ help }
					value={ value || '' }
					onChange={ handleChange }
				/>
			);
	}
};

export default OptionControl;
