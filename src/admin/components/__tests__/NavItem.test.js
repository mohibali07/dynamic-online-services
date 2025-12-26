import { render, screen, fireEvent } from '@testing-library/react';
import '@testing-library/jest-dom';
import NavItem from '../NavItem';

// Mock WordPress components
jest.mock( '@wordpress/components', () => ( {
	Button: ( props ) => <button { ...props }>{ props.children }</button>,
	Tooltip: ( { children } ) => <div>{ children }</div>,
	Dashicon: () => <span>Icon</span>,
} ) );

describe( 'NavItem', () => {
	const mockProps = {
		id: 'test-tab',
		label: 'Test Tab',
		icon: 'admin-generic',
		activeTab: 'general',
		setActiveTab: jest.fn(),
	};

	it( 'renders correctly', () => {
		render( <NavItem { ...mockProps } /> );

		const button = screen.getByRole( 'button', { name: 'Test Tab' } );
		expect( button ).toBeInTheDocument();
		expect( button ).toHaveTextContent( 'Test Tab' );
	} );

	it( 'applies active class when active', () => {
		render( <NavItem { ...mockProps } activeTab="test-tab" /> );

		const button = screen.getByRole( 'button', { name: 'Test Tab' } );
		expect( button ).toHaveClass( 'active' );
		expect( button ).toHaveAttribute( 'aria-current', 'page' );
	} );

	it( 'calls setActiveTab on click', () => {
		render( <NavItem { ...mockProps } /> );

		const button = screen.getByRole( 'button', { name: 'Test Tab' } );
		fireEvent.click( button );

		expect( mockProps.setActiveTab ).toHaveBeenCalledWith( 'test-tab' );
	} );
} );
