import { PanelBody, SelectControl } from '@wordpress/components';
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
						'Hero Description Color',
						'dynamic-online-services'
					) }
					help={ __(
						'Color of the hero description text.',
						'dynamic-online-services'
					) }
					type="color"
					optionKey="hero_description_color"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Hero Overlay Color',
						'dynamic-online-services'
					) }
					help={ __(
						'This color will be semi-transparent over the background image.',
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
					type="range"
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
						'Hero section height for desktop. Supports px, vh, %. Example: 60vh',
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
						'Hero section height for tablets. Supports px, vh, %. Example: 50vh',
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
						'Hero section height for mobile. Supports px, vh, %. Example: 50vh',
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
				<SelectControl
					label={ __( 'Google Font', 'dynamic-online-services' ) }
					help={ __(
						'Choose a Google Font for hero titles.',
						'dynamic-online-services'
					) }
					value={ settings?.hero_google_font || 'sans-serif' }
					options={ [
						{ label: 'Default (sans-serif)', value: 'sans-serif' },
						{ label: 'Inter', value: 'Inter' },
						{ label: 'Roboto', value: 'Roboto' },
						{ label: 'Open Sans', value: 'Open Sans' },
						{ label: 'Montserrat', value: 'Montserrat' },
						{ label: 'Poppins', value: 'Poppins' },
						{
							label: 'Playfair Display',
							value: 'Playfair Display',
						},
						{ label: 'Lato', value: 'Lato' },
						{ label: 'Raleway', value: 'Raleway' },
						{ label: 'Merriweather', value: 'Merriweather' },
					] }
					onChange={ ( value ) =>
						onChange( 'hero_google_font', value )
					}
				/>

				<OptionControl
					label={ __( 'Font Weight', 'dynamic-online-services' ) }
					help={ __(
						'Font weights to load (comma-separated, e.g., 400,700).',
						'dynamic-online-services'
					) }
					type="text"
					optionKey="hero_font_weight"
					settings={ settings }
					onChange={ onChange }
				/>

				<hr style={ { margin: '20px 0' } } />
				<h3>{ __( 'Title Font Sizes', 'dynamic-online-services' ) }</h3>
				<OptionControl
					label={ __(
						'Title Font Size (Desktop)',
						'dynamic-online-services'
					) }
					help={ __(
						'Font size for the hero title on desktop. Example: 3rem',
						'dynamic-online-services'
					) }
					optionKey="hero_title_font_size"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Title Font Size (Tablet)',
						'dynamic-online-services'
					) }
					help={ __(
						'Font size for the hero title on tablets. Example: 2.5rem',
						'dynamic-online-services'
					) }
					optionKey="hero_title_font_size_tablet"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Title Font Size (Mobile)',
						'dynamic-online-services'
					) }
					help={ __(
						'Font size for the hero title on mobile. Example: 2rem',
						'dynamic-online-services'
					) }
					optionKey="hero_title_font_size_mobile"
					settings={ settings }
					onChange={ onChange }
				/>

				<hr style={ { margin: '20px 0' } } />
				<h3>
					{ __(
						'Description Font Sizes',
						'dynamic-online-services'
					) }
				</h3>
				<OptionControl
					label={ __(
						'Description Font Size (Desktop)',
						'dynamic-online-services'
					) }
					help={ __(
						'Font size for the hero description on desktop. Example: 1.25rem',
						'dynamic-online-services'
					) }
					optionKey="hero_description_font_size"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Description Font Size (Tablet)',
						'dynamic-online-services'
					) }
					help={ __(
						'Font size for the hero description on tablets. Example: 1rem',
						'dynamic-online-services'
					) }
					optionKey="hero_description_font_size_tablet"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Description Font Size (Mobile)',
						'dynamic-online-services'
					) }
					help={ __(
						'Font size for the hero description on mobile. Example: 0.9rem',
						'dynamic-online-services'
					) }
					optionKey="hero_description_font_size_mobile"
					settings={ settings }
					onChange={ onChange }
				/>
			</PanelBody>

			<PanelBody
				title={ __( 'Layout & Alignment', 'dynamic-online-services' ) }
				initialOpen={ false }
			>
				<OptionControl
					label={ __( 'Content Padding', 'dynamic-online-services' ) }
					help={ __(
						'Padding inside the hero content box. Example: 20px',
						'dynamic-online-services'
					) }
					optionKey="hero_content_padding"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __( 'Border Radius', 'dynamic-online-services' ) }
					help={ __(
						'Rounded corners radius for content box. Example: 10px',
						'dynamic-online-services'
					) }
					optionKey="hero_border_radius"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __( 'Margin Bottom', 'dynamic-online-services' ) }
					help={ __(
						'Space below the hero section. Example: 30px',
						'dynamic-online-services'
					) }
					optionKey="hero_margin_bottom"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Content Max Width',
						'dynamic-online-services'
					) }
					help={ __(
						'Maximum width of content box. Example: 90%, 800px',
						'dynamic-online-services'
					) }
					optionKey="hero_content_max_width"
					settings={ settings }
					onChange={ onChange }
				/>
				<SelectControl
					label={ __( 'Text Alignment', 'dynamic-online-services' ) }
					help={ __(
						'Horizontal text alignment inside the hero.',
						'dynamic-online-services'
					) }
					value={ settings?.hero_text_alignment || 'center' }
					options={ [
						{
							label: __( 'Left', 'dynamic-online-services' ),
							value: 'left',
						},
						{
							label: __( 'Center', 'dynamic-online-services' ),
							value: 'center',
						},
						{
							label: __( 'Right', 'dynamic-online-services' ),
							value: 'right',
						},
					] }
					onChange={ ( value ) =>
						onChange( 'hero_text_alignment', value )
					}
				/>
				<SelectControl
					label={ __(
						'Vertical Alignment',
						'dynamic-online-services'
					) }
					help={ __(
						'Vertical position of content inside the hero.',
						'dynamic-online-services'
					) }
					value={ settings?.hero_vertical_alignment || 'center' }
					options={ [
						{
							label: __( 'Top', 'dynamic-online-services' ),
							value: 'top',
						},
						{
							label: __( 'Center', 'dynamic-online-services' ),
							value: 'center',
						},
						{
							label: __( 'Bottom', 'dynamic-online-services' ),
							value: 'bottom',
						},
					] }
					onChange={ ( value ) =>
						onChange( 'hero_vertical_alignment', value )
					}
				/>
			</PanelBody>

			{ /* CTA Button Panel */ }
			<PanelBody
				title={ __( 'CTA Button', 'dynamic-online-services' ) }
				initialOpen={ false }
			>
				<OptionControl
					label={ __(
						'Enable CTA Button',
						'dynamic-online-services'
					) }
					help={ __(
						'Show a call-to-action button in the hero.',
						'dynamic-online-services'
					) }
					type="checkbox"
					optionKey="hero_cta_enabled"
					settings={ settings }
					onChange={ onChange }
				/>
				{ settings?.hero_cta_enabled && (
					<>
						<OptionControl
							label={ __(
								'Button Text',
								'dynamic-online-services'
							) }
							help={ __(
								'Text displayed on the button.',
								'dynamic-online-services'
							) }
							type="text"
							optionKey="hero_cta_text"
							settings={ settings }
							onChange={ onChange }
						/>
						<OptionControl
							label={ __(
								'Button URL',
								'dynamic-online-services'
							) }
							help={ __(
								'Link URL for the button.',
								'dynamic-online-services'
							) }
							type="text"
							optionKey="hero_cta_url"
							settings={ settings }
							onChange={ onChange }
						/>
						<SelectControl
							label={ __(
								'Button Style',
								'dynamic-online-services'
							) }
							help={ __(
								'Visual style of the button.',
								'dynamic-online-services'
							) }
							value={ settings?.hero_cta_style || 'primary' }
							options={ [
								{
									label: __(
										'Primary',
										'dynamic-online-services'
									),
									value: 'primary',
								},
								{
									label: __(
										'Secondary',
										'dynamic-online-services'
									),
									value: 'secondary',
								},
								{
									label: __(
										'Outline',
										'dynamic-online-services'
									),
									value: 'outline',
								},
							] }
							onChange={ ( value ) =>
								onChange( 'hero_cta_style', value )
							}
						/>
						<OptionControl
							label={ __(
								'Open in New Tab',
								'dynamic-online-services'
							) }
							help={ __(
								'Open link in a new browser tab.',
								'dynamic-online-services'
							) }
							type="checkbox"
							optionKey="hero_cta_new_tab"
							settings={ settings }
							onChange={ onChange }
						/>
					</>
				) }
			</PanelBody>

			{ /* Background Video Panel */ }
			<PanelBody
				title={ __( 'Background Video', 'dynamic-online-services' ) }
				initialOpen={ false }
			>
				<OptionControl
					label={ __(
						'Enable Video Background',
						'dynamic-online-services'
					) }
					help={ __(
						'Use a video as the hero background.',
						'dynamic-online-services'
					) }
					type="checkbox"
					optionKey="hero_video_enabled"
					settings={ settings }
					onChange={ onChange }
				/>
				{ settings?.hero_video_enabled && (
					<>
						<OptionControl
							label={ __(
								'Video URL',
								'dynamic-online-services'
							) }
							help={ __(
								'Full URL to the video file (MP4 recommended).',
								'dynamic-online-services'
							) }
							type="text"
							optionKey="hero_video_url"
							settings={ settings }
							onChange={ onChange }
						/>
						<OptionControl
							label={ __(
								'Poster Image URL',
								'dynamic-online-services'
							) }
							help={ __(
								'Fallback image shown before video loads.',
								'dynamic-online-services'
							) }
							type="text"
							optionKey="hero_video_poster"
							settings={ settings }
							onChange={ onChange }
						/>
						<OptionControl
							label={ __(
								'Autoplay',
								'dynamic-online-services'
							) }
							help={ __(
								'Automatically play video on page load.',
								'dynamic-online-services'
							) }
							type="checkbox"
							optionKey="hero_video_autoplay"
							settings={ settings }
							onChange={ onChange }
						/>
						<OptionControl
							label={ __( 'Loop', 'dynamic-online-services' ) }
							help={ __(
								'Loop the video continuously.',
								'dynamic-online-services'
							) }
							type="checkbox"
							optionKey="hero_video_loop"
							settings={ settings }
							onChange={ onChange }
						/>
						<OptionControl
							label={ __( 'Mute', 'dynamic-online-services' ) }
							help={ __(
								'Mute the video audio.',
								'dynamic-online-services'
							) }
							type="checkbox"
							optionKey="hero_video_muted"
							settings={ settings }
							onChange={ onChange }
						/>
					</>
				) }
			</PanelBody>

			{ /* Parallax Effect Panel */ }
			<PanelBody
				title={ __( 'Parallax Effect', 'dynamic-online-services' ) }
				initialOpen={ false }
			>
				<OptionControl
					label={ __( 'Enable Parallax', 'dynamic-online-services' ) }
					help={ __(
						'Apply CSS-only parallax scrolling effect to background.',
						'dynamic-online-services'
					) }
					type="checkbox"
					optionKey="hero_parallax_enabled"
					settings={ settings }
					onChange={ onChange }
				/>
				{ settings?.hero_parallax_enabled && (
					<p className="dynos-info-box">
						{ __(
							'Parallax effect respects prefers-reduced-motion. Uses CSS background-attachment: fixed for GPU acceleration.',
							'dynamic-online-services'
						) }
					</p>
				) }
			</PanelBody>
		</div>
	);
};

export default HeroSettings;
