import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	SelectControl,
	RangeControl,
	ToggleControl,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody
					title={ __( 'Settings', 'dynamic-online-services' ) }
				>
					<TextControl
						label={ __(
							'Category (Slug or ID)',
							'dynamic-online-services'
						) }
						value={ attributes.category }
						onChange={ ( category ) =>
							setAttributes( { category } )
						}
						help={ __(
							'Comma separated slugs or IDs.',
							'dynamic-online-services'
						) }
					/>
					<TextControl
						label={ __( 'Post IDs', 'dynamic-online-services' ) }
						value={ attributes.ids }
						onChange={ ( ids ) => setAttributes( { ids } ) }
						help={ __(
							'Specific post IDs to include.',
							'dynamic-online-services'
						) }
					/>
					<RangeControl
						label={ __( 'Columns', 'dynamic-online-services' ) }
						value={ attributes.columns }
						onChange={ ( columns ) => setAttributes( { columns } ) }
						min={ window.dynosSettings?.minGridColumns || 1 }
						max={ window.dynosSettings?.maxGridColumns || 6 }
					/>
					<TextControl
						label={ __( 'Limit', 'dynamic-online-services' ) }
						type="number"
						value={ attributes.limit }
						onChange={ ( limit ) =>
							setAttributes( { limit: parseInt( limit ) } )
						}
					/>
					<SelectControl
						label={ __( 'Order By', 'dynamic-online-services' ) }
						value={ attributes.orderby }
						options={ [
							{ label: 'Date', value: 'date' },
							{ label: 'Title', value: 'title' },
							{ label: 'Menu Order', value: 'menu_order' },
							{ label: 'Random', value: 'rand' },
						] }
						onChange={ ( orderby ) => setAttributes( { orderby } ) }
					/>
					<SelectControl
						label={ __( 'Order', 'dynamic-online-services' ) }
						value={ attributes.order }
						options={ [
							{ label: 'Descending', value: 'DESC' },
							{ label: 'Ascending', value: 'ASC' },
						] }
						onChange={ ( order ) => setAttributes( { order } ) }
					/>
					<ToggleControl
						label={ __(
							'Show Pagination',
							'dynamic-online-services'
						) }
						checked={ attributes.showPagination }
						onChange={ ( showPagination ) =>
							setAttributes( { showPagination } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<ServerSideRender
				block="dynamic-online-services/service-cards"
				attributes={ attributes }
			/>
		</div>
	);
}
