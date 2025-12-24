import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	SelectControl,
	RangeControl,
	ToggleControl,
	Placeholder,
	Notice,
} from '@wordpress/components';
import { Icon, info, grid } from '@wordpress/icons';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Error Placeholder Component
 * Displays a user-friendly message when the server-side render fails.
 */
const ErrorPlaceholder = () => (
	<Placeholder
		icon={<Icon icon={info} />}
		label={__('Service Cards', 'dynamic-online-services')}
		instructions={__(
			'Unable to load the preview. The block will render correctly on the frontend.',
			'dynamic-online-services'
		)}
	>
		<Notice status="warning" isDismissible={false}>
			{__(
				'Check your server connection or try refreshing the editor.',
				'dynamic-online-services'
			)}
		</Notice>
	</Placeholder>
);

/**
 * Empty Placeholder Component
 * Displays when no services are found matching the criteria.
 */
const EmptyPlaceholder = () => (
	<Placeholder
		icon={<Icon icon={grid} />}
		label={__('Service Cards', 'dynamic-online-services')}
		instructions={__(
			'No services found. Try adjusting your filter settings or add some services first.',
			'dynamic-online-services'
		)}
	/>
);

export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps();

	return (
		<div {...blockProps}>
			<InspectorControls>
				<PanelBody
					title={__('Settings', 'dynamic-online-services')}
				>
					<TextControl
						label={__(
							'Category (Slug or ID)',
							'dynamic-online-services'
						)}
						value={attributes.category}
						onChange={(category) =>
							setAttributes({ category })
						}
						help={__(
							'Comma separated slugs or IDs.',
							'dynamic-online-services'
						)}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
					<TextControl
						label={__('Post IDs', 'dynamic-online-services')}
						value={attributes.ids}
						onChange={(ids) => setAttributes({ ids })}
						help={__(
							'Specific post IDs to include.',
							'dynamic-online-services'
						)}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
					<RangeControl
						label={__('Columns', 'dynamic-online-services')}
						value={attributes.columns}
						onChange={(columns) => setAttributes({ columns })}
						min={window.dynosSettings?.minGridColumns || 1}
						max={window.dynosSettings?.maxGridColumns || 6}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
					<TextControl
						label={__('Limit', 'dynamic-online-services')}
						type="number"
						value={attributes.limit}
						onChange={(limit) =>
							setAttributes({ limit: parseInt(limit) })
						}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
					<SelectControl
						label={__('Order By', 'dynamic-online-services')}
						value={attributes.orderby}
						options={[
							{ label: 'Date', value: 'date' },
							{ label: 'Title', value: 'title' },
							{ label: 'Menu Order', value: 'menu_order' },
							{ label: 'Random', value: 'rand' },
						]}
						onChange={(orderby) => setAttributes({ orderby })}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
					<SelectControl
						label={__('Order', 'dynamic-online-services')}
						value={attributes.order}
						options={[
							{ label: 'Descending', value: 'DESC' },
							{ label: 'Ascending', value: 'ASC' },
						]}
						onChange={(order) => setAttributes({ order })}
						__nextHasNoMarginBottom
						__next40pxDefaultSize
					/>
					<ToggleControl
						label={__(
							'Show Pagination',
							'dynamic-online-services'
						)}
						checked={attributes.showPagination}
						onChange={(showPagination) =>
							setAttributes({ showPagination })
						}
						__nextHasNoMarginBottom
					/>
				</PanelBody>
			</InspectorControls>
			<ServerSideRender
				block="dynamic-online-services/service-cards"
				attributes={attributes}
				EmptyResponsePlaceholder={EmptyPlaceholder}
				ErrorResponsePlaceholder={ErrorPlaceholder}
			/>
		</div>
	);
}
