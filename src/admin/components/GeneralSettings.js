import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import OptionControl from './OptionControl';

const GeneralSettings = ( { settings, onChange } ) => {
	return (
		<div className="dynos-tab-content">
			<h2>{ __( 'General Settings', 'dynamic-online-services' ) }</h2>

			<PanelBody
				title={ __( 'Post Type', 'dynamic-online-services' ) }
				initialOpen={ true }
			>
				<OptionControl
					label={ __(
						'Service Post Type Slug',
						'dynamic-online-services'
					) }
					help={ __(
						'URL slug for the service post type. Change requires flushing rewrite rules.',
						'dynamic-online-services'
					) }
					optionKey="service_post_type_slug"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Service Taxonomy Slug',
						'dynamic-online-services'
					) }
					help={ __(
						'URL slug for the service category taxonomy.',
						'dynamic-online-services'
					) }
					optionKey="service_taxonomy_slug"
					settings={ settings }
					onChange={ onChange }
				/>
			</PanelBody>

			<PanelBody
				title={ __( 'Uninstall', 'dynamic-online-services' ) }
				initialOpen={ false }
			>
				<div className="dynos-warning-box">
					<OptionControl
						label={ __(
							'Delete Data on Uninstall',
							'dynamic-online-services'
						) }
						help={ __(
							'Remove all data when plugin is deleted.',
							'dynamic-online-services'
						) }
						type="toggle"
						optionKey="delete_data_on_uninstall"
						settings={ settings }
						onChange={ onChange }
					/>
					<p>
						{ __(
							'⚠️ Warning: Enabling this will permanently delete all services and settings when you uninstall the plugin.',
							'dynamic-online-services'
						) }
					</p>
				</div>
			</PanelBody>
		</div>
	);
};

export default GeneralSettings;
