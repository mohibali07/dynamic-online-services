import {
	TabPanel,
	Spinner,
	Button,
	SnackbarList,
	Dashicon,
} from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

import HeroSettings from './components/HeroSettings';
import CardSettings from './components/CardSettings';
import FaqSettings from './components/FaqSettings';
import GeneralSettings from './components/GeneralSettings';
import WhatsAppSettings from './components/WhatsAppSettings';

const SettingsApp = () => {
	const [settings, setSettings] = useEntityProp(
		'root',
		'site',
		'dynos_options'
	);
	const { saveEditedEntityRecord } = useDispatch('core');
	const [isSaving, setIsSaving] = useState(false);
	const [notices, setNotices] = useState([]);

	const updateSettings = (newSettings) => {
		setSettings({
			...settings,
			...newSettings,
		});
	};

	const saveSettings = async () => {
		setIsSaving(true);
		try {
			await saveEditedEntityRecord(
				'root',
				'site',
				settings.id
			);
			setNotices([
				...notices,
				{
					id: 'save-success',
					content: __('Settings saved successfully.', 'dynamic-online-services'),
					status: 'success',
				},
			]);
		} catch (error) {
			setNotices([
				...notices,
				{
					id: 'save-error',
					content: __('Error saving settings.', 'dynamic-online-services'),
					status: 'error',
				},
			]);
		} finally {
			setIsSaving(false);
		}
	};

	const removeNotice = (id) => {
		setNotices(notices.filter((notice) => notice.id !== id));
	};

	if (!settings) {
		return <Spinner />;
	}

	const pluginSettings = settings || {};

	return (
		<div className="wrap dynos-settings-wrap">
			<div className="dynos-header">
				<h1>{__('Dynamic Online Services', 'dynamic-online-services')}</h1>
				<Button
					isPrimary
					onClick={saveSettings}
					isBusy={isSaving}
					disabled={isSaving}
				>
					{isSaving
						? __('Saving...', 'dynamic-online-services')
						: __('Save Settings', 'dynamic-online-services')}
				</Button>
			</div>

			<SnackbarList notices={notices} onRemove={removeNotice} />

			<TabPanel
				className="dynos-settings-tabs"
				activeClass="active-tab"
				tabs={[
					{
						name: 'general',
						title: (
							<>
								<Dashicon icon="admin-settings" /> {__('General', 'dynamic-online-services')}
							</>
						),
						className: 'tab-general',
					},
					{
						name: 'hero',
						title: (
							<>
								<Dashicon icon="cover-image" /> {__('Hero Section', 'dynamic-online-services')}
							</>
						),
						className: 'tab-hero',
					},
					{
						name: 'cards',
						title: (
							<>
								<Dashicon icon="grid-view" /> {__('Service Cards', 'dynamic-online-services')}
							</>
						),
						className: 'tab-cards',
					},
					{
						name: 'faqs',
						title: (
							<>
								<Dashicon icon="format-chat" /> {__('FAQs', 'dynamic-online-services')}
							</>
						),
						className: 'tab-faqs',
					},
					{
						name: 'whatsapp',
						title: (
							<>
								<Dashicon icon="whatsapp" /> {__('WhatsApp', 'dynamic-online-services')}
							</>
						),
						className: 'tab-whatsapp',
					},
				]}
			>
				{(tab) => {
					switch (tab.name) {
						case 'general':
							return (
								<GeneralSettings
									settings={pluginSettings}
									onChange={updateSettings}
								/>
							);
						case 'hero':
							return (
								<HeroSettings
									settings={pluginSettings}
									onChange={updateSettings}
								/>
							);
						case 'cards':
							return (
								<CardSettings
									settings={pluginSettings}
									onChange={updateSettings}
								/>
							);
						case 'faqs':
							return (
								<FaqSettings
									settings={pluginSettings}
									onChange={updateSettings}
								/>
							);
						case 'whatsapp':
							return (
								<WhatsAppSettings
									settings={pluginSettings}
									onChange={updateSettings}
								/>
							);
						default:
							return null;
					}
				}}
			</TabPanel>
		</div>
	);
};

export default SettingsApp;
