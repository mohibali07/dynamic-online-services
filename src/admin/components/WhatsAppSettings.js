import {
	PanelBody,
	SelectControl,
	TextareaControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import OptionControl from './OptionControl';

const WhatsAppSettings = ( { settings, onChange } ) => {
	const handleSelectChange = ( key, value ) => {
		onChange( {
			...settings,
			[ key ]: value,
		} );
	};

	return (
		<div className="dynos-tab-content">
			<h2>
				{ __( 'WhatsApp Configuration', 'dynamic-online-services' ) }
			</h2>

			<PanelBody
				title={ __( 'General', 'dynamic-online-services' ) }
				initialOpen={ true }
			>
				<OptionControl
					label={ __(
						'Enable Floating Button',
						'dynamic-online-services'
					) }
					help={ __(
						'Display the WhatsApp button on the frontend.',
						'dynamic-online-services'
					) }
					type="toggle"
					optionKey="whatsapp_enabled"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __( 'Phone Number', 'dynamic-online-services' ) }
					help={ __(
						'Enter number with country code (e.g., 923001234567).',
						'dynamic-online-services'
					) }
					optionKey="whatsapp_number"
					settings={ settings }
					onChange={ onChange }
				/>
				<TextareaControl
					label={ __(
						'Pre-filled Message',
						'dynamic-online-services'
					) }
					help={ __(
						'Message to send when user clicks the button.',
						'dynamic-online-services'
					) }
					value={ settings.whatsapp_message || '' }
					onChange={ ( value ) =>
						handleSelectChange( 'whatsapp_message', value )
					}
				/>
			</PanelBody>

			<PanelBody
				title={ __( 'Appearance', 'dynamic-online-services' ) }
				initialOpen={ false }
			>
				<SelectControl
					label={ __( 'Position', 'dynamic-online-services' ) }
					value={ settings.whatsapp_position || 'right' }
					options={ [
						{
							label: __(
								'Bottom Right',
								'dynamic-online-services'
							),
							value: 'right',
						},
						{
							label: __(
								'Bottom Left',
								'dynamic-online-services'
							),
							value: 'left',
						},
					] }
					onChange={ ( value ) =>
						handleSelectChange( 'whatsapp_position', value )
					}
				/>
				<OptionControl
					label={ __(
						'Background Color',
						'dynamic-online-services'
					) }
					help={ __(
						'Button background color.',
						'dynamic-online-services'
					) }
					type="color"
					optionKey="whatsapp_bg_color"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __( 'Icon Color', 'dynamic-online-services' ) }
					help={ __( 'Icon color.', 'dynamic-online-services' ) }
					type="color"
					optionKey="whatsapp_icon_color"
					settings={ settings }
					onChange={ onChange }
				/>
			</PanelBody>
		</div>
	);
};

export default WhatsAppSettings;
