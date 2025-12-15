import { PanelBody, PanelRow } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import OptionControl from './OptionControl';

const CardSettings = ( { settings, onChange } ) => {
	return (
		<div className="dynos-tab-content">
			<h2>
				{ __( 'Service Cards Settings', 'dynamic-online-services' ) }
			</h2>

			<PanelBody
				title={ __( 'Colors', 'dynamic-online-services' ) }
				initialOpen={ true }
			>
				<OptionControl
					label={ __(
						'Card Background Color',
						'dynamic-online-services'
					) }
					help={ __(
						'The background color of the service card.',
						'dynamic-online-services'
					) }
					type="color"
					optionKey="card_bg_color"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Card Title Color',
						'dynamic-online-services'
					) }
					help={ __(
						'Color of the card title text.',
						'dynamic-online-services'
					) }
					type="color"
					optionKey="card_title_color"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Card Description Color',
						'dynamic-online-services'
					) }
					help={ __(
						'Color of the card description text.',
						'dynamic-online-services'
					) }
					type="color"
					optionKey="card_description_color"
					settings={ settings }
					onChange={ onChange }
				/>
			</PanelBody>

			<PanelBody
				title={ __( 'Typography', 'dynamic-online-services' ) }
				initialOpen={ false }
			>
				<OptionControl
					label={ __(
						'Card Title Font Size',
						'dynamic-online-services'
					) }
					help={ __(
						'Font size for the card title.',
						'dynamic-online-services'
					) }
					optionKey="card_title_font_size"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Card Description Font Size',
						'dynamic-online-services'
					) }
					help={ __(
						'Font size for the card description text.',
						'dynamic-online-services'
					) }
					optionKey="card_description_font_size"
					settings={ settings }
					onChange={ onChange }
				/>
			</PanelBody>

			<PanelBody
				title={ __(
					'Dimensions & Spacing',
					'dynamic-online-services'
				) }
				initialOpen={ false }
			>
				<OptionControl
					label={ __(
						'Card Border Radius',
						'dynamic-online-services'
					) }
					help={ __(
						'Rounding of the card corners.',
						'dynamic-online-services'
					) }
					optionKey="card_border_radius"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __( 'Card Height', 'dynamic-online-services' ) }
					help={ __(
						'Total height of the card element.',
						'dynamic-online-services'
					) }
					optionKey="card_height"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Card Content Padding',
						'dynamic-online-services'
					) }
					help={ __(
						'Space on the left and right of the content.',
						'dynamic-online-services'
					) }
					optionKey="card_content_padding"
					settings={ settings }
					onChange={ onChange }
				/>
			</PanelBody>
			<PanelBody
				title={ __( 'Grid Layout', 'dynamic-online-services' ) }
				initialOpen={ false }
			>
				<OptionControl
					label={ __( 'Grid Gap', 'dynamic-online-services' ) }
					help={ __(
						'Horizontal space between cards in the grid.',
						'dynamic-online-services'
					) }
					optionKey="grid_gap"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __( 'Grid Row Gap', 'dynamic-online-services' ) }
					help={ __(
						'Vertical space between rows of cards.',
						'dynamic-online-services'
					) }
					optionKey="grid_row_gap"
					settings={ settings }
					onChange={ onChange }
				/>
			</PanelBody>
		</div>
	);
};

export default CardSettings;
