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
 * Re usable control component for rendering different types of setting inputs
 * within the dynos_options object. Supports text, number, color, and toggle controls.
 *
 * @since 1.0.0
 *
 * @param {Object}   props                Component props.
 * @param {string}   props.label          Label for the setting control.
 * @param {string}   [props.help]         Optional help text/description displayed below the control.
 * @param {string}   [props.type='text']  Control type: 'text', 'number', 'color', 'toggle', 'checkbox'.
 * @param {string}   props.optionKey      The key in dynos_options to read/write.
 * @param {Object}   props.settings       The full settings object containing all dynos_options.
 * @param {Function} props.onChange       Callback function when value changes. Receives updated settings object.
 * @param {*}        [props.defaultValue] Default value to use if setting is undefined.
 * @param {Function} [props.validate]     Optional validation function. Returns error message string or null if valid.
 *
 * @return {JSX.Element} The rendered control component.
 *
 * @example
 * <OptionControl
 *   label="Site Title"
 *   help="The name of your site"
 *   type="text"
 *   optionKey="site_title"
 *   settings={settings}
 *   onChange={setSettings}
 * />
 */
const OptionControl = ({
	label,
	help,
	type = 'text',
	optionKey,
	settings,
	onChange,
	min,
	max,
	step,
	options,
	placeholder,
}) => {
	// Get value from settings or use default
	const value =
		settings[optionKey] !== undefined
			? settings[optionKey]
			: defaultValue;

	/**
	 * Handle value change with optional validation.
	 *
	 * @param {*} newValue The new value from the control.
	 *
	 * @return {void}
	 */
	const handleChange = (newValue) => {
		// Run validation if provided
		if (validate && typeof validate === 'function') {
			const errorMessage = validate(newValue);
			if (errorMessage) {
				// Log validation error
				if (typeof window.sosConsoleLog === 'function') {
					window.sosConsoleLog(
						'warn',
						`Validation failed for ${optionKey}:`,
						errorMessage
					);
				}
				// Could potentially show error to user here
				return;
			}
		}

		// Update settings
		onChange({
			...settings,
			[optionKey]: newValue,
		});
	};

	// Render appropriate control based on type
	switch (type) {
		case 'toggle':
		case 'checkbox':
			return (
				<ToggleControl
					label={label}
					help={help}
					checked={!!value}
					onChange={handleChange}
				/>
			);

		case 'color':
			return (
				<BaseControl label={label} help={help}>
					<ColorPalette
						colors={
							window.dynosSettings && window.dynosSettings.colorPresets
								? window.dynosSettings.colorPresets
								: [
									{ name: __('Black', 'dynamic-online-services'), color: '#000000' },
									{ name: __('White', 'dynamic-online-services'), color: '#ffffff' },
									{ name: __('Red', 'dynamic-online-services'), color: '#f00' },
									{ name: __('Blue', 'dynamic-online-services'), color: '#00f' },
									{ name: __('Gray', 'dynamic-online-services'), color: '#999' },
								]
						}
						value={value || ''}
						onChange={handleChange}
						clearable={true}
					/>
				</BaseControl>
			);

		case 'number':
			return (
				<TextControl
					label={label}
					help={help}
					type="number"
					value={value !== undefined ? value : ''}
					onChange={handleChange}
					min={min}
					max={max}
					step={step}
				/>
			);

		case 'radio':
			// For radio, we want a list of radio inputs
			// But WordPress components RadioControl is better suited if available,
			// checking imports... we only imported ToggleControl, TextControl, etc.
			// Let's use BaseControl with native inputs or RadioControl if we add it to imports.
			// Let's assume we can add RadioControl to imports or implement simple radio list.
			// We will modify imports in a separate step or just use standard HTML for now to be safe,
			// or better, let's use the TextControl as fallback if options missing, but here we have options.
			return (
				<BaseControl label={label} help={help}>
					{options &&
						Object.entries(options).map(([optValue, optLabel]) => (
							<div key={optValue} style={{ marginBottom: '8px' }}>
								<label style={{ display: 'flex', alignItems: 'center' }}>
									<input
										type="radio"
										name={optionKey}
										value={optValue}
										checked={value === optValue}
										onChange={() => handleChange(optValue)}
										style={{ marginRight: '8px' }}
									/>
									{optLabel}
								</label>
							</div>
						))}
				</BaseControl>
			);

		default:
			// Default to text input
			return (
				<TextControl
					label={label}
					help={help}
					value={value || ''}
					onChange={handleChange}
					placeholder={placeholder}
				/>
			);
	}
};

export default OptionControl;

