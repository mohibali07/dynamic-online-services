import { useEffect, useState } from '@wordpress/element';
import PropTypes from 'prop-types';

/**
 * Success Animation Component
 * Displays an animated checkmark for successful operations
 * @param root0
 * @param root0.show
 * @param root0.onComplete
 */
const SuccessAnimation = ( { show = false, onComplete } ) => {
	const [ visible, setVisible ] = useState( show );

	useEffect( () => {
		if ( show ) {
			setVisible( true );
			const timer = setTimeout( () => {
				setVisible( false );
				if ( onComplete ) {
					onComplete();
				}
			}, 2000 );

			return () => clearTimeout( timer );
		}
		return undefined;
	}, [ show, onComplete ] );

	if ( ! visible ) {
		return null;
	}

	return (
		<span
			className="dynos-success-checkmark"
			role="img"
			aria-label="Success"
		>
			<svg viewBox="0 0 52 52" xmlns="http://www.w3.org/2000/svg">
				<circle
					cx="26"
					cy="26"
					r="25"
					fill="none"
					stroke="currentColor"
					strokeWidth="2"
				/>
				<path
					fill="none"
					stroke="currentColor"
					strokeWidth="3"
					strokeLinecap="round"
					strokeLinejoin="round"
					d="M14 27l7.5 7.5L38 18"
				/>
			</svg>
		</span>
	);
};

SuccessAnimation.propTypes = {
	show: PropTypes.bool,
	onComplete: PropTypes.func,
};

SuccessAnimation.defaultProps = {
	show: false,
	onComplete: null,
};

export default SuccessAnimation;
