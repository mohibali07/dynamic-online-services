import { render, screen, fireEvent } from '@testing-library/react';
import '@testing-library/jest-dom';
import SettingsApp from '../SettingsApp';

// Mock WordPress dependencies
jest.mock( '@wordpress/components', () => ( {
	Button: ( { isBusy, isDestructive, ...props } ) => (
		<button { ...props }>{ props.children }</button>
	),
	SnackbarList: () => <div role="status">SnackbarList</div>,
	Dashicon: () => (
		<span role="img" aria-hidden="true">
			Icon
		</span>
	),
	Tooltip: ( { children } ) => <div>{ children }</div>,
} ) );

jest.mock( '@wordpress/data', () => ( {
	useDispatch: jest.fn( () => ( {
		saveEditedEntityRecord: jest.fn(),
	} ) ),
} ) );

jest.mock( '@wordpress/core-data', () => ( {
	useEntityProp: jest.fn( () => [ [], jest.fn() ] ),
} ) );

jest.mock( '@wordpress/element', () => ( {
	...jest.requireActual( '@wordpress/element' ),
	useState: jest.requireActual( 'react' ).useState,
} ) );

// Mock child components with accessible roles
jest.mock( '../components/NavItem', () => ( props ) => (
	<button onClick={ () => props.setActiveTab( props.id ) }>
		{ props.label }
	</button>
) );
jest.mock( '../components/GeneralSettings', () => () => (
	<div role="tabpanel" aria-label="General Settings Panel">
		General Settings Panel
	</div>
) );
jest.mock( '../components/HeroSettings', () => () => (
	<div role="tabpanel" aria-label="Hero Settings Panel">
		Hero Settings Panel
	</div>
) );
jest.mock( '../components/SuccessAnimation', () => () => (
	<div data-testid="success-animation">Success Animation</div>
) );

describe( 'SettingsApp Unit Tests', () => {
	it( 'should render the application and default to the "General" tab', () => {
		render( <SettingsApp /> );

		// Verify General Settings is displayed by default
		expect(
			screen.getByRole( 'tabpanel', { name: /General Settings Panel/i } ) // Use regex for flexible matching if text matches
		).toBeInTheDocument();
	} );

	it( 'should switch to "Hero Section" tab when clicked', () => {
		render( <SettingsApp /> );

		// Act: Click on Hero Section tab
		const heroTab = screen.getByRole( 'button', { name: 'Hero Section' } );
		fireEvent.click( heroTab );

		// Assert: Hero Settings panel is visible
		expect(
			screen.getByRole( 'tabpanel', { name: /Hero Settings Panel/i } )
		).toBeInTheDocument();

		// Assert: General Settings panel is NOT visible (optional but good for strictness)
		expect(
			screen.queryByText( 'General Settings Panel' )
		).not.toBeInTheDocument();
	} );
} );
