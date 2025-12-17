import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import OptionControl from './OptionControl';

/**
 * GeneralSettings Component.
 *
 * Renders general plugin settings including post type configuration and uninstall options.
 *
 * @since 1.0.0
 *
 * @param {Object}   props          Component props.
 * @param {Object}   props.settings Current settings object.
 * @param {Function} props.onChange Callback to update settings.
 *
 * @return {JSX.Element} The GeneralSettings component.
 */
const GeneralSettings = ({ settings, onChange }) => {
	return (
		<div className="dynos-tab-content">
			<h2>{__('General Settings', 'dynamic-online-services')}</h2>

			<PanelBody
				title={__('Post Type', 'dynamic-online-services')}
				initialOpen={true}
			>
				<OptionControl
					label={__(
						'Service Post Type Slug',
						'dynamic-online-services'
					)}
					help={__(
						'URL slug for the service post type. Change requires flushing rewrite rules.',
						'dynamic-online-services'
					)}
					optionKey="service_post_type_slug"
					settings={settings}
					onChange={onChange}
				/>
				<OptionControl
					label={__(
						'Service Taxonomy Slug',
						'dynamic-online-services'
					)}
					help={__(
						'URL slug for the service category taxonomy.',
						'dynamic-online-services'
					)}
					optionKey="service_taxonomy_slug"
					settings={settings}
					onChange={onChange}
				/>
			</PanelBody>

			<PanelBody
				title={__('Uninstall', 'dynamic-online-services')}
				initialOpen={false}
			>
				<OptionControl
					label={__(
						'Delete Data on Uninstall',
						'dynamic-online-services'
					)}
					help={__(
						'Remove all data when plugin is deleted.',
						'dynamic-online-services'
					)}
					type="toggle"
					optionKey="delete_data_on_uninstall"
					settings={settings}
					onChange={onChange}
				/>
			</PanelBody>
		</div>
	);
};

export default GeneralSettings;
