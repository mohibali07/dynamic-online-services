import { TabPanel, Spinner, Button, SnackbarList } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { useState, useMemo } from '@wordpress/element';

import DynamicSettings from './components/DynamicSettings';

/**
 * SettingsApp Component.
 *
 * Main settings application component that manages plugin settings using WordPress entity data.
 * Provides tabbed interface for different setting categories.
 *
 * @since 1.0.0
 *
 * @return {JSX.Element} The SettingsApp component.
 */
const SettingsApp = () => {
	const [settings, setSettings] = useEntityProp(
		'root',
		'site',
		'dynos_options'
	);
	const { saveEditedEntityRecord } = useDispatch('core');
	const [isSaving, setIsSaving] = useState(false);
	const [notices, setNotices] = useState([]);

	// Get configuration from localized script
	// dynosSettings is injected via wp_localize_script
	const configMap = window.dynosSettings?.fields || {};

	// Generate tabs from config
	const tabs = useMemo(() => {
		return Object.entries(configMap).map(([key, section]) => ({
			name: key,
			title: section.title,
			className: `tab-${key}`,
			id: section.id,
		}));
	}, [configMap]);

	if (!settings) {
		return <Spinner />;
	}

	const handleSave = async () => {
		setIsSaving(true);
		try {
			await saveEditedEntityRecord('root', 'site');
			setNotices([
				{
					id: 'saved',
					content: __(
						'Settings saved successfully.',
						'dynamic-online-services'
					),
					status: 'success',
				},
			]);
		} catch (error) {
			setNotices([
				{ id: 'error', content: error.message, status: 'error' },
			]);
		}
		setIsSaving(false);
	};

	const removeNotice = (id) => {
		setNotices(notices.filter((notice) => notice.id !== id));
	};

	return (
		<div className="wrap dynos-settings-wrap">
			<div
				className="dynos-header"
				style={{
					display: 'flex',
					justifyContent: 'space-between',
					alignItems: 'center',
					marginBottom: '20px',
				}}
			>
				<h1>
					{__(
						'Dynamic Services Settings',
						'dynamic-online-services'
					)}
				</h1>
				<Button variant="primary" isBusy={isSaving} onClick={handleSave}>
					{__('Save Settings', 'dynamic-online-services')}
				</Button>
			</div>

			<SnackbarList notices={notices} onRemove={removeNotice} />

			{tabs.length > 0 ? (
				<TabPanel
					className="dynos-settings-tabs"
					activeClass="active-tab"
					tabs={tabs}
				>
					{(tab) => (
						<DynamicSettings
							section={configMap[tab.name]}
							settings={settings}
							onChange={setSettings}
						/>
					)}
				</TabPanel>
			) : (
				<p>{__('No settings configuration found.', 'dynamic-online-services')}</p>
			)}
		</div>
	);
};

export default SettingsApp;
