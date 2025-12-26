import { useState } from '@wordpress/element';
import { Dashicon } from '@wordpress/components';
import PropTypes from 'prop-types';

/**
 * Tooltip Component
 * Reusable tooltip for contextual help
 *
 * @param {Object} props          Component props.
 * @param {string} props.content  Tooltip content.
 * @param {Object} props.children Trigger element (optional).
 * @param {string} props.position Tooltip position: 'top', 'bottom', 'left', 'right'.
 */
const Tooltip = ( { content, children, position = 'top' } ) => {
	const [ isVisible, setIsVisible ] = useState( false );

	if ( ! content ) {
		return children || null;
	}

	return (
		<span
			className="dynos-tooltip"
			onMouseEnter={ () => setIsVisible( true ) }
			onMouseLeave={ () => setIsVisible( false ) }
			onFocus={ () => setIsVisible( true ) }
			onBlur={ () => setIsVisible( false ) }
		>
			{ children || (
				<Dashicon
					icon="info-outline"
					className="dynos-tooltip-trigger"
					aria-label="More information"
				/>
			) }
			{ isVisible && (
				<span
					className={ `dynos-tooltip-content dynos-tooltip-${ position }` }
					role="tooltip"
				>
					{ content }
				</span>
			) }
		</span>
	);
};

Tooltip.propTypes = {
	content: PropTypes.string,
	children: PropTypes.node,
	position: PropTypes.oneOf( [ 'top', 'bottom', 'left', 'right' ] ),
};

Tooltip.defaultProps = {
	content: '',
	children: null,
	position: 'top',
};

export default Tooltip;
