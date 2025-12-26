import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	RichText,
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	ToggleControl,
	ColorPalette,
	RangeControl,
	SelectControl,
	Button,
} from '@wordpress/components';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps({
		className: 'dynos-hero-section-edit',
		style: {
			backgroundImage: attributes.desktopImageUrl
				? `url(${attributes.desktopImageUrl})`
				: undefined,
			minHeight: attributes.minHeightDesktop,
			display: 'flex',
			alignItems: 'center',
			justifyContent: 'center',
			position: 'relative',
			backgroundSize: 'cover',
			backgroundPosition: 'center',
		},
	});

	const onSelectDesktopImage = (media) => {
		setAttributes({
			desktopImageUrl: media.url,
			desktopImageId: media.id,
		});
	};

	const onSelectMobileImage = (media) => {
		setAttributes({
			mobileImageUrl: media.url,
			mobileImageId: media.id,
		});
	};

	return (
		<div {...blockProps}>
			<InspectorControls>
				<PanelBody
					title={__(
						'Background & Media',
						'dynamic-online-services'
					)}
				>
					<p>{__('Desktop Image', 'dynamic-online-services')}</p>
					<MediaUpload
						onSelect={onSelectDesktopImage}
						type="image"
						value={attributes.desktopImageId}
						render={({ open }) => (
							<Button variant="secondary" onClick={open}>
								{attributes.desktopImageUrl
									? __(
										'Replace Desktop Image',
										'dynamic-online-services'
									)
									: __(
										'Upload Desktop Image',
										'dynamic-online-services'
									)}
							</Button>
						)}
					/>

					<hr />

					<p>
						{__(
							'Mobile Image (Optional)',
							'dynamic-online-services'
						)}
					</p>
					<MediaUpload
						onSelect={onSelectMobileImage}
						type="image"
						value={attributes.mobileImageId}
						render={({ open }) => (
							<Button variant="secondary" onClick={open}>
								{attributes.mobileImageUrl
									? __(
										'Replace Mobile Image',
										'dynamic-online-services'
									)
									: __(
										'Upload Mobile Image',
										'dynamic-online-services'
									)}
							</Button>
						)}
					/>

					<hr />

					<ToggleControl
						label={__(
							'Enable Parallax',
							'dynamic-online-services'
						)}
						checked={attributes.enableParallax}
						onChange={(val) =>
							setAttributes({ enableParallax: val })
						}
						__nextHasNoMarginBottom
					/>
					<ToggleControl
						label={__(
							'Enable Ken Burns Effect',
							'dynamic-online-services'
						)}
						checked={attributes.enableKenBurns}
						onChange={(val) =>
							setAttributes({ enableKenBurns: val })
						}
						__nextHasNoMarginBottom
					/>
					<TextControl
						label={__(
							'Minimum Height (Desktop)',
							'dynamic-online-services'
						)}
						value={attributes.minHeightDesktop}
						onChange={(val) =>
							setAttributes({ minHeightDesktop: val })
						}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
					<TextControl
						label={__(
							'Minimum Height (Mobile)',
							'dynamic-online-services'
						)}
						value={attributes.minHeightMobile}
						onChange={(val) =>
							setAttributes({ minHeightMobile: val })
						}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>

					<SelectControl
						label={__(
							'Shape Divider',
							'dynamic-online-services'
						)}
						value={attributes.shapeDivider}
						options={[
							{ label: 'None', value: 'none' },
							{ label: 'Waves', value: 'waves' },
							{ label: 'Curve', value: 'curve' },
							{ label: 'Triangle', value: 'triangle' },
						]}
						onChange={(val) =>
							setAttributes({ shapeDivider: val })
						}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
				</PanelBody>

				<PanelBody
					title={__(
						'Time-Based Backgrounds',
						'dynamic-online-services'
					)}
					initialOpen={false}
				>
					<p>
						{__(
							'Dynamic Backgrounds based on user local time.',
							'dynamic-online-services'
						)}
					</p>
					<p style={{ fontSize: '12px', color: '#666' }}>
						<em>
							{__(
								'Note: This feature uses server time to determine the image.',
								'dynamic-online-services'
							)}
						</em>
					</p>

					<MediaUpload
						onSelect={(media) =>
							setAttributes({ morningImageUrl: media.url })
						}
						type="image"
						value={attributes.morningImageUrl}
						render={({ open }) => (
							<Button
								variant="secondary"
								onClick={open}
								style={{
									marginBottom: '10px',
									width: '100%',
								}}
							>
								{attributes.morningImageUrl
									? __(
										'Replace Morning Image',
										'dynamic-online-services'
									)
									: __(
										'Upload Morning Image (Default)',
										'dynamic-online-services'
									)}
							</Button>
						)}
					/>
					<MediaUpload
						onSelect={(media) =>
							setAttributes({ eveningImageUrl: media.url })
						}
						type="image"
						value={attributes.eveningImageUrl}
						render={({ open }) => (
							<Button
								variant="secondary"
								onClick={open}
								style={{
									marginBottom: '10px',
									width: '100%',
								}}
							>
								{attributes.eveningImageUrl
									? __(
										'Replace Evening Image',
										'dynamic-online-services'
									)
									: __(
										'Upload Evening Image',
										'dynamic-online-services'
									)}
							</Button>
						)}
					/>

					<RangeControl
						label={__(
							'Evening Start Hour (24h)',
							'dynamic-online-services'
						)}
						value={attributes.eveningStartHour || 18}
						onChange={(val) =>
							setAttributes({ eveningStartHour: val })
						}
						min={0}
						max={23}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
				</PanelBody>

				<PanelBody
					title={__(
						'Overlay Settings',
						'dynamic-online-services'
					)}
				>
					<p>{__('Overlay Color', 'dynamic-online-services')}</p>
					<ColorPalette
						value={attributes.overlayColor}
						onChange={(val) =>
							setAttributes({ overlayColor: val })
						}
					/>
					<RangeControl
						label={__('Opacity', 'dynamic-online-services')}
						value={attributes.overlayOpacity}
						onChange={(val) =>
							setAttributes({ overlayOpacity: val })
						}
						min={0}
						max={1}
						step={0.1}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
				</PanelBody>

				<PanelBody
					title={__(
						'Content Settings',
						'dynamic-online-services'
					)}
				>
					<SelectControl
						label={__(
							'Title HTML Tag',
							'dynamic-online-services'
						)}
						value={attributes.titleTag}
						options={[
							{ label: 'H1', value: 'h1' },
							{ label: 'H2', value: 'h2' },
							{ label: 'Div', value: 'div' },
						]}
						onChange={(val) =>
							setAttributes({ titleTag: val })
						}
					/>
					<TextControl
						label={__(
							'Primary Button Text',
							'dynamic-online-services'
						)}
						value={attributes.btnPrimaryText}
						onChange={(val) =>
							setAttributes({ btnPrimaryText: val })
						}
					/>
					<TextControl
						label={__(
							'Primary Button URL',
							'dynamic-online-services'
						)}
						value={attributes.btnPrimaryUrl}
						onChange={(val) =>
							setAttributes({ btnPrimaryUrl: val })
						}
					/>
					<TextControl
						label={__(
							'Secondary Button Text',
							'dynamic-online-services'
						)}
						value={attributes.btnSecondaryText}
						onChange={(val) =>
							setAttributes({ btnSecondaryText: val })
						}
					/>
				</PanelBody>
			</InspectorControls>

			{ /* Editor Preview */}
			<div
				className="dynos-hero-overlay-preview"
				style={{
					position: 'absolute',
					top: 0,
					left: 0,
					width: '100%',
					height: '100%',
					backgroundColor: attributes.overlayColor,
					opacity: attributes.overlayOpacity,
					zIndex: 1,
				}}
			></div>

			<div
				className="dynos-hero-content-preview"
				style={{
					position: 'relative',
					zIndex: 2,
					padding: '20px',
					textAlign: 'center',
					width: '100%',
				}}
			>
				<RichText
					tagName={attributes.titleTag}
					className="dynos-hero-title"
					value={attributes.title}
					onChange={(val) => setAttributes({ title: val })}
					placeholder={__(
						'Write title…',
						'dynamic-online-services'
					)}
					style={{ color: '#fff' }}
				/>
				<RichText
					tagName="p"
					className="dynos-hero-subtitle"
					value={attributes.subtitle}
					onChange={(val) => setAttributes({ subtitle: val })}
					placeholder={__(
						'Write subtitle…',
						'dynamic-online-services'
					)}
					style={{ color: '#fff', fontSize: '1.2rem' }}
				/>
				<div className="dynos-hero-buttons">
					{attributes.btnPrimaryText && (
						<span
							className="dynos-btn-primary"
							style={{
								background: '#fff',
								color: '#333',
								padding: '10px 20px',
								display: 'inline-block',
								margin: '5px',
							}}
						>
							{attributes.btnPrimaryText}
						</span>
					)}
					{attributes.btnSecondaryText && (
						<span
							className="dynos-btn-secondary"
							style={{
								border: '2px solid #fff',
								color: '#fff',
								padding: '10px 20px',
								display: 'inline-block',
								margin: '5px',
							}}
						>
							{attributes.btnSecondaryText}
						</span>
					)}
				</div>
			</div>
		</div>
	);
}
