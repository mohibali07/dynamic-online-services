import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import OptionControl from './OptionControl';

/**
 * DynamicSettings Component.
 *
 * Renders a specific section of settings based on the provided configuration.
 *
 * @since 1.2.0
 *
 * @param {Object}   props          Component props.
 * @param {Object}   props.section  The section configuration object.
 * @param {Object}   props.settings Current settings object.
 * @param {Function} props.onChange Callback to update settings.
 *
 * @return {JSX.Element} The DynamicSettings component.
 */
const DynamicSettings = ({ section, settings, onChange }) => {
	if (!section || !section.fields) {
		return null;
	}

	return (
		<div className="dynos-tab-content">
			<h2>{section.title}</h2>

			<PanelBody title={section.title} initialOpen={true}>
				{Object.entries(section.fields).map(([fieldKey, fieldConfig]) => (
					<OptionControl
						key={fieldKey}
						label={fieldConfig.title}
						help={fieldConfig.args?.description}
						type={mapCallbackToType(fieldConfig.callback)}
						optionKey={fieldKey}
						settings={settings}
						onChange={onChange}
						min={fieldConfig.args?.min}
						max={fieldConfig.args?.max}
						step={fieldConfig.args?.step}
						options={fieldConfig.args?.options}
						placeholder={fieldConfig.args?.placeholder}
					/>
				))}
			</PanelBody>
		</div>
	);
};

/**
 * Helper to map PHP callback names to OptionControl types.
 *
 * @param {string} callback The PHP callback function name.
 * @return {string} The corresponding control type.
 */
const mapCallbackToType = (callback) => {
	switch (callback) {
		case 'dynos_color_field_callback':
			return 'color';
		case 'dynos_number_field_callback':
			return 'number';
		case 'dynos_checkbox_field_callback':
			return 'checkbox';
		case 'dynos_radio_field_callback':
			return 'radio';
		case 'dynos_font_family_field_callback':
			return 'text';
		case 'dynos_text_field_callback':
		default:
			return 'text';
	}
};

export default DynamicSettings;
