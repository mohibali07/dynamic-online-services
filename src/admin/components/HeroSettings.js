import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import OptionControl from './OptionControl';

const HeroSettings = ( { settings, onChange } ) => {
	return (
		<div className="dynos-tab-content">
			<h2>
				{ __( 'Hero Section Settings', 'dynamic-online-services' ) }
			</h2>

			<PanelBody
				title={ __( 'Colors & Overlay', 'dynamic-online-services' ) }
				initialOpen={ true }
			>
				<OptionControl
					label={ __(
						'Hero Title Color',
						'dynamic-online-services'
					) }
					help={ __(
						'Color of the main hero title.',
						'dynamic-online-services'
					) }
					type="color"
					optionKey="hero_title_color"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Hero Overlay Color',
						'dynamic-online-services'
					) }
					help={ __(
						'This color will be semi-transparent.',
						'dynamic-online-services'
					) }
					type="color"
					optionKey="hero_overlay_color"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Hero Overlay Opacity',
						'dynamic-online-services'
					) }
					help={ __(
						'Overlay opacity from 0 (transparent) to 1 (opaque).',
						'dynamic-online-services'
					) }
					type="number"
					optionKey="hero_overlay_opacity"
					settings={ settings }
					onChange={ onChange }
				/>
			</PanelBody>

			<PanelBody
				title={ __( 'Dimensions', 'dynamic-online-services' ) }
				initialOpen={ false }
			>
				<OptionControl
					label={ __(
						'Hero Height (Desktop)',
						'dynamic-online-services'
					) }
					help={ __(
						'Hero section height for desktop. Use px, vh, or %.',
						'dynamic-online-services'
					) }
					optionKey="hero_height"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Hero Height (Tablet)',
						'dynamic-online-services'
					) }
					help={ __(
						'Hero section height for tablets.',
						'dynamic-online-services'
					) }
					optionKey="hero_height_tablet"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Hero Height (Mobile)',
						'dynamic-online-services'
					) }
					help={ __(
						'Hero section height for mobile.',
						'dynamic-online-services'
					) }
					optionKey="hero_height_mobile"
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
						'Hero Title Font Size',
						'dynamic-online-services'
					) }
					help={ __(
						'Font size for the hero title on desktop.',
						'dynamic-online-services'
					) }
					optionKey="hero_title_font_size"
					settings={ settings }
					onChange={ onChange }
				/>
			</PanelBody>
		</div>
	);
};

export default HeroSettings;
