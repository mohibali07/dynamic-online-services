import { Spinner, Button, SnackbarList, Dashicon } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';

import HeroSettings from './components/HeroSettings';
import CardSettings from './components/CardSettings';
import FaqSettings from './components/FaqSettings';
import GeneralSettings from './components/GeneralSettings';
import WhatsAppSettings from './components/WhatsAppSettings';

// Import modern admin styles
import './admin.css';

const SettingsApp = () => {
	const [ settings, setSettings ] = useEntityProp(
		'root',
		'site',
		'dynos_options'
	);
	const { saveEditedEntityRecord } = useDispatch( 'core' );
	const [ isSaving, setIsSaving ] = useState( false );
	const [ notices, setNotices ] = useState( [] );
	const [ activeTab, setActiveTab ] = useState( 'general' );

	const updateSettings = ( newSettings ) => {
		setSettings( {
			...settings,
			...newSettings,
		} );
	};

	const saveSettings = async () => {
		setIsSaving( true );
		try {
			await saveEditedEntityRecord( 'root', 'site', settings.id );
			setNotices( [
				...notices,
				{
					id: 'save-success',
					content: __(
						'Settings saved successfully.',
						'dynamic-online-services'
					),
					status: 'success',
				},
			] );
		} catch ( error ) {
			setNotices( [
				...notices,
				{
					id: 'save-error',
					content: __(
						'Error saving settings.',
						'dynamic-online-services'
					),
					status: 'error',
				},
			] );
		} finally {
			setIsSaving( false );
		}
	};

	const removeNotice = ( id ) => {
		setNotices( notices.filter( ( notice ) => notice.id !== id ) );
	};

	if ( ! settings ) {
		return <Spinner />;
	}

	const pluginSettings = settings || {};

	// Navigation Items
	const navItems = [
		{
			id: 'general',
			label: __( 'General', 'dynamic-online-services' ),
			icon: 'admin-settings',
			component: GeneralSettings,
		},
		{
			id: 'hero',
			label: __( 'Hero Section', 'dynamic-online-services' ),
			icon: 'cover-image',
			component: HeroSettings,
		},
		{
			id: 'cards',
			label: __( 'Service Cards', 'dynamic-online-services' ),
			icon: 'grid-view',
			component: CardSettings,
		},
		{
			id: 'faqs',
			label: __( 'FAQs', 'dynamic-online-services' ),
			icon: 'format-chat',
			component: FaqSettings,
		},
		{
			id: 'whatsapp',
			label: __( 'WhatsApp Widget', 'dynamic-online-services' ),
			icon: 'whatsapp',
			component: WhatsAppSettings,
		},
	];

	const ActiveComponent =
		navItems.find( ( item ) => item.id === activeTab )?.component ||
		GeneralSettings;

	return (
		<div className="dynos-settings-wrap">
			<div className="dynos-app-container">
				{ /* Vertical Sidebar */ }
				<aside className="dynos-sidebar">
					<div className="dynos-sidebar-header">
						<h1>
							<Dashicon icon="welcome-learn-more" />
							<span>DynOS</span>
						</h1>
					</div>

					<nav className="dynos-sidebar-nav">
						{ navItems.map( ( item ) => (
							<button
								key={ item.id }
								type="button"
								className={ `dynos-nav-item ${
									activeTab === item.id ? 'active' : ''
								}` }
								onClick={ () => setActiveTab( item.id ) }
							>
								<Dashicon icon={ item.icon } />
								{ item.label }
							</button>
						) ) }
					</nav>

					<div className="dynos-sidebar-footer">
						<Button
							className="dynos-save-btn"
							onClick={ saveSettings }
							isBusy={ isSaving }
							disabled={ isSaving }
						>
							{ isSaving
								? __( 'Saving…', 'dynamic-online-services' )
								: __(
										'Save Changes',
										'dynamic-online-services'
								  ) }
						</Button>
					</div>
				</aside>

				{ /* Main Content Area */ }
				<main className="dynos-main-content">
					<SnackbarList
						notices={ notices }
						onRemove={ removeNotice }
						className="components-snackbar-list"
					/>

					<div className="dynos-content-header">
						<h2 className="dynos-section-title">
							{
								navItems.find( ( i ) => i.id === activeTab )
									?.label
							}
						</h2>
						<p className="dynos-section-desc">
							{ __(
								'Manage settings for this section below.',
								'dynamic-online-services'
							) }
						</p>
					</div>

					<ActiveComponent
						settings={ pluginSettings }
						onChange={ updateSettings }
					/>
				</main>
			</div>
		</div>
	);
};

export default SettingsApp;
