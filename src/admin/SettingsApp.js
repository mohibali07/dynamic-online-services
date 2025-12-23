import { Button, SnackbarList, Dashicon } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

import HeroSettings from './components/HeroSettings';
import CardSettings from './components/CardSettings';
import FaqSettings from './components/FaqSettings';
import GeneralSettings from './components/GeneralSettings';
import WhatsAppSettings from './components/WhatsAppSettings';
import LoadingSkeleton from './components/LoadingSkeleton';
import SuccessAnimation from './components/SuccessAnimation';

import NavItem from './components/NavItem';

// Import constants and hooks
import { TABS, KEYBOARD_SHORTCUTS } from './constants';
import { useKeyboardShortcut } from './hooks/useKeyboardShortcut';

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
	const [ saveSuccess, setSaveSuccess ] = useState( false );
	const [ notices, setNotices ] = useState( [] );
	const [ activeTab, setActiveTab ] = useState( TABS.GENERAL );

	const updateSettings = ( newSettings ) => {
		setSettings( {
			...settings,
			...newSettings,
		} );
	};

	const saveSettings = async () => {
		setIsSaving( true );
		setSaveSuccess( false );
		try {
			await saveEditedEntityRecord( 'root', 'site', settings.id );
			setSaveSuccess( true );
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

	// Keyboard shortcut: Cmd/Ctrl + S to save
	useKeyboardShortcut( KEYBOARD_SHORTCUTS.SAVE, () => {
		if ( ! isSaving ) {
			saveSettings();
		}
	} );

	if ( ! settings ) {
		return <LoadingSkeleton />;
	}

	const pluginSettings = settings || {};

	// Navigation Items
	const navItems = [
		{
			id: TABS.GENERAL,
			label: __( 'General', 'dynamic-online-services' ),
			icon: 'admin-settings',
			component: GeneralSettings,
		},
		{
			id: TABS.HERO,
			label: __( 'Hero Section', 'dynamic-online-services' ),
			icon: 'cover-image',
			component: HeroSettings,
		},
		{
			id: TABS.CARDS,
			label: __( 'Service Cards', 'dynamic-online-services' ),
			icon: 'grid-view',
			component: CardSettings,
		},
		{
			id: TABS.FAQS,
			label: __( 'FAQs', 'dynamic-online-services' ),
			icon: 'format-chat',
			component: FaqSettings,
		},
		{
			id: TABS.WHATSAPP,
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
							<Dashicon icon="superhero-alt" />
							<span>DynOS</span>
						</h1>
					</div>

					<nav className="dynos-sidebar-nav">
						{ navItems.map( ( item ) => (
							<NavItem
								key={ item.id }
								id={ item.id }
								label={ item.label }
								icon={ item.icon }
								activeTab={ activeTab }
								setActiveTab={ setActiveTab }
							/>
						) ) }
					</nav>

					<div className="dynos-sidebar-footer">
						<Button
							className={ `dynos-save-btn ${
								saveSuccess ? 'is-success' : ''
							}` }
							onClick={ saveSettings }
							isBusy={ isSaving }
							disabled={ isSaving }
							aria-live="polite"
						>
							{ isSaving
								? __( 'Saving…', 'dynamic-online-services' )
								: __(
										'Save Changes',
										'dynamic-online-services'
								  ) }
							<SuccessAnimation
								show={ saveSuccess }
								onComplete={ () => setSaveSuccess( false ) }
							/>
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

					<div className="dynos-panel-wrapper">
						<ActiveComponent
							settings={ pluginSettings }
							onChange={ updateSettings }
						/>
					</div>
				</main>
			</div>
		</div>
	);
};

export default SettingsApp;
