import { TabPanel, Spinner, Button, SnackbarList } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

import GeneralSettings from './components/GeneralSettings';
import HeroSettings from './components/HeroSettings';
import CardSettings from './components/CardSettings';
import FaqSettings from './components/FaqSettings';

const SettingsApp = () => {
	const [ settings, setSettings ] = useEntityProp(
		'root',
		'site',
		'dynos_options'
	);
	const { saveEditedEntityRecord } = useDispatch( 'core' );
	const [ isSaving, setIsSaving ] = useState( false );
	const [ notices, setNotices ] = useState( [] );

	if ( ! settings ) {
		return <Spinner />;
	}

	const handleSave = async () => {
		setIsSaving( true );
		try {
			await saveEditedEntityRecord( 'root', 'site' );
			setNotices( [
				{
					id: 'saved',
					content: __(
						'Settings saved successfully.',
						'dynamic-online-services'
					),
					status: 'success',
				},
			] );
		} catch ( error ) {
			setNotices( [
				{ id: 'error', content: error.message, status: 'error' },
			] );
		}
		setIsSaving( false );
	};

	const removeNotice = ( id ) => {
		setNotices( notices.filter( ( notice ) => notice.id !== id ) );
	};

	return (
		<div className="wrap dynos-settings-wrap">
			<div
				className="dynos-header"
				style={ {
					display: 'flex',
					justifyContent: 'space-between',
					alignItems: 'center',
					marginBottom: '20px',
				} }
			>
				<h1>
					{ __(
						'Dynamic Services Settings',
						'dynamic-online-services'
					) }
				</h1>
				<Button isPrimary isBusy={ isSaving } onClick={ handleSave }>
					{ __( 'Save Settings', 'dynamic-online-services' ) }
				</Button>
			</div>

			<SnackbarList notices={ notices } onRemove={ removeNotice } />

			<TabPanel
				className="dynos-settings-tabs"
				activeClass="active-tab"
				tabs={ [
					{
						name: 'general',
						title: __( 'General', 'dynamic-online-services' ),
						className: 'tab-general',
					},
					{
						name: 'hero',
						title: __( 'Hero Section', 'dynamic-online-services' ),
						className: 'tab-hero',
					},
					{
						name: 'cards',
						title: __( 'Service Cards', 'dynamic-online-services' ),
						className: 'tab-cards',
					},
					{
						name: 'faqs',
						title: __( 'FAQs', 'dynamic-online-services' ),
						className: 'tab-faqs',
					},
				] }
			>
				{ ( tab ) => {
					switch ( tab.name ) {
						case 'general':
							return (
								<GeneralSettings
									settings={ settings }
									onChange={ setSettings }
								/>
							);
						case 'hero':
							return (
								<HeroSettings
									settings={ settings }
									onChange={ setSettings }
								/>
							);
						case 'cards':
							return (
								<CardSettings
									settings={ settings }
									onChange={ setSettings }
								/>
							);
						case 'faqs':
							return (
								<FaqSettings
									settings={ settings }
									onChange={ setSettings }
								/>
							);
						default:
							return null;
					}
				} }
			</TabPanel>
		</div>
	);
};

export default SettingsApp;
