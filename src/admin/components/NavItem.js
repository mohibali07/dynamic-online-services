import { Button, Tooltip, Dashicon } from '@wordpress/components';
import PropTypes from 'prop-types';

/**
 * NavItem component encapsulates a navigation button with proper ARIA attributes.
 * @param {Object}   props
 * @param {string}   props.id           - Tab identifier.
 * @param {string}   props.label        - Display label.
 * @param {string}   props.icon         - Dashicon name.
 * @param {string}   props.activeTab    - Currently active tab.
 * @param {Function} props.setActiveTab - Function to change active tab.
 */
const NavItem = ( { id, label, icon, activeTab, setActiveTab } ) => (
	<Tooltip content={ label } position="right">
		<Button
			type="button"
			className={ `dynos-nav-item ${ activeTab === id ? 'active' : '' }` }
			onClick={ () => setActiveTab( id ) }
			aria-label={ label }
			aria-current={ activeTab === id ? 'page' : undefined }
		>
			<Dashicon icon={ icon } />
			{ label }
		</Button>
	</Tooltip>
);

NavItem.propTypes = {
	id: PropTypes.string.isRequired,
	label: PropTypes.string.isRequired,
	icon: PropTypes.string.isRequired,
	activeTab: PropTypes.string.isRequired,
	setActiveTab: PropTypes.func.isRequired,
};

export default NavItem;
