import {
	TextControl,
	ToggleControl,
	ColorPalette,
	BaseControl,
	RangeControl,
	Popover,
	Button,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * OptionControl Component.
 *
 * Renders a control for a specific setting key within the dynos_options object.
 *
 * @param {Object}   props
 * @param {string}   props.label     Label for the setting.
 * @param {string}   props.help      Help text/Description.
 * @param {string}   props.type      'text', 'number', 'color', 'toggle', 'range', 'unit'.
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
	const [ showPopover, setShowPopover ] = useState( false );

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
				<BaseControl label={ label } help={ help } id={ optionKey }>
					<div
						style={ {
							display: 'flex',
							alignItems: 'center',
							gap: '10px',
						} }
					>
						<Button
							style={ {
								backgroundColor: value,
								width: '36px',
								height: '36px',
								borderRadius: '50%',
								border: '1px solid #ccc',
								cursor: 'pointer',
							} }
							onClick={ () => setShowPopover( ! showPopover ) }
							aria-label={ __(
								'Select color',
								'dynamic-online-services'
							) }
						/>
						<code>{ value }</code>
						{ showPopover && (
							<Popover
								position="bottom left"
								onClose={ () => setShowPopover( false ) }
							>
								<div style={ { padding: '16px' } }>
									<ColorPalette
										colors={
											window.dynosSettings
												?.colorPresets || [
												{
													name: 'Black',
													color: '#000000',
												},
												{
													name: 'White',
													color: '#ffffff',
												},
												{ name: 'Red', color: '#f00' },
												{ name: 'Blue', color: '#00f' },
											]
										}
										value={ value }
										onChange={ handleChange }
										clearable
									/>
								</div>
							</Popover>
						) }
					</div>
				</BaseControl>
			);

		case 'range':
			return (
				<RangeControl
					label={ label }
					help={ help }
					value={ value }
					onChange={ handleChange }
					min={ 0 }
					max={ 1 }
					step={ 0.1 }
				/>
			);

		case 'unit':
			return (
				<UnitControl
					label={ label }
					help={ help }
					value={ value }
					onChange={ handleChange }
					units={ [
						{ value: 'px', label: 'px', default: 0 },
						{ value: '%', label: '%', default: 0 },
						{ value: 'vh', label: 'vh', default: 0 },
						{ value: 'vw', label: 'vw', default: 0 },
						{ value: 'rem', label: 'rem', default: 0 },
						{ value: 'em', label: 'em', default: 0 },
					] }
				/>
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
