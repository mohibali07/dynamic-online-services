import {
	TextControl,
	ToggleControl,
	ColorPalette,
	RangeControl,
	Popover,
	Button,
	SelectControl,
	TextareaControl,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import PropTypes from 'prop-types';

/**
 * OptionControl Component.
 *
 * Renders a control for a specific setting key within the dynos_options object.
 *
 * @param {Object}   props
 * @param {string}   props.label     Label for the setting.
 * @param {string}   props.help      Help text/Description.
 * @param {string}   props.type      'text', 'number', 'color', 'toggle', 'range', 'unit', 'select', 'textarea'.
 * @param {string}   props.optionKey The key in dynos_options.
 * @param {Object}   props.settings  The full settings object.
 * @param {Function} props.onChange  Callback when value changes.
 * @param {Array}    [props.options] Optional options for 'select' type.
 */
const OptionControl = ( {
	label,
	help,
	type,
	optionKey,
	settings,
	onChange,
	options,
} ) => {
	const value = settings[ optionKey ];
	const [ showPopover, setShowPopover ] = useState( false );

	const handleChange = ( newValue ) => {
		onChange( {
			...settings,
			[ optionKey ]: newValue,
		} );
	};

	const renderControl = () => {
		switch ( type ) {
			case 'toggle':
			case 'checkbox':
				return (
					<ToggleControl
						checked={ !! value }
						onChange={ handleChange }
					/>
				);
			case 'color':
				return (
					<div className="dynos-color-picker-trigger">
						<Button
							className="dynos-color-swatch"
							style={ { backgroundColor: value } }
							onClick={ () => setShowPopover( ! showPopover ) }
							aria-label={ __(
								'Select color',
								'dynamic-online-services'
							) }
						/>
						<code className="dynos-color-code">{ value }</code>
						{ showPopover && (
							<Popover
								position="bottom left"
								onClose={ () => setShowPopover( false ) }
							>
								<div className="dynos-popover-content">
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
												{
													name: 'Red',
													color: '#ef4444',
												},
												{
													name: 'Blue',
													color: '#3b82f6',
												},
												{
													name: 'Green',
													color: '#10b981',
												},
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
				);

			case 'range':
				return (
					<RangeControl
						value={ value }
						onChange={ handleChange }
						min={ 0 }
						max={ 1 }
						step={ 0.1 }
						withInputField={ false }
					/>
				);

			case 'unit':
				return (
					<UnitControl
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
			case 'select':
				return (
					<SelectControl
						value={ value }
						options={ options }
						onChange={ handleChange }
					/>
				);
			case 'textarea':
				return (
					<TextareaControl
						value={ value }
						onChange={ handleChange }
					/>
				);
			case 'number':
				return (
					<TextControl
						type="number"
						value={ value }
						onChange={ handleChange }
					/>
				);
			case 'tel':
				return (
					<TextControl
						type="tel"
						value={ value }
						onChange={ handleChange }
					/>
				);
			default:
				return (
					<TextControl
						value={ value || '' }
						onChange={ handleChange }
					/>
				);
		}
	};

	return (
		<div className="dynos-option-control">
			<div className="dynos-control-header">
				{ /* eslint-disable-next-line jsx-a11y/label-has-associated-control */ }
				<label className="dynos-control-label">{ label }</label>
				{ help && <span className="dynos-control-help">{ help }</span> }
			</div>
			<div className="dynos-control-input">{ renderControl() }</div>
		</div>
	);
};

OptionControl.propTypes = {
	label: PropTypes.string.isRequired,
	help: PropTypes.string,
	type: PropTypes.oneOf( [
		'text',
		'number',
		'color',
		'toggle',
		'checkbox',
		'range',
		'unit',
		'tel',
		'select',
		'textarea',
	] ),
	optionKey: PropTypes.string.isRequired,
	settings: PropTypes.object.isRequired,
	onChange: PropTypes.func.isRequired,
	options: PropTypes.arrayOf(
		PropTypes.shape( {
			label: PropTypes.string,
			value: PropTypes.string,
		} )
	),
};

OptionControl.defaultProps = {
	help: '',
	type: 'text',
};

export default OptionControl;
