import { Component } from '@wordpress/element';
import PropTypes from 'prop-types';

/**
 * ErrorBoundary Component
 *
 * Catches JavaScript errors anywhere in the child component tree,
 * logs those errors, and displays a fallback UI instead of crashing.
 *
 * @example
 * <ErrorBoundary>
 *   <YourComponent />
 * </ErrorBoundary>
 */
class ErrorBoundary extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			hasError: false,
			error: null,
			errorInfo: null,
		};
	}

	static getDerivedStateFromError() {
		// Update state so the next render will show the fallback UI
		return { hasError: true };
	}

	componentDidCatch( error, errorInfo ) {
		// Log error to console in development
		if ( process.env.NODE_ENV === 'development' ) {
			console.error( 'ErrorBoundary caught an error:', error, errorInfo );
		}

		// Update state with error details
		this.setState( {
			error,
			errorInfo,
		} );

		// You can also log the error to an error reporting service here
		// logErrorToService(error, errorInfo);
	}

	handleReset = () => {
		this.setState( {
			hasError: false,
			error: null,
			errorInfo: null,
		} );
	};

	render() {
		if ( this.state.hasError ) {
			// Custom fallback UI
			if ( this.props.fallback ) {
				return this.props.fallback;
			}

			// Default fallback UI
			return (
				<div
					style={ {
						padding: '2rem',
						backgroundColor: '#fee2e2',
						border: '1px solid #ef4444',
						borderRadius: '0.5rem',
						margin: '1rem',
					} }
				>
					<h2 style={ { color: '#991b1b', marginTop: 0 } }>
						⚠️ Something went wrong
					</h2>
					<p style={ { color: '#7f1d1d' } }>
						{ this.props.errorMessage ||
							'An unexpected error occurred. Please try refreshing the page.' }
					</p>
					<details
						style={ {
							marginTop: '1rem',
							padding: '1rem',
							backgroundColor: '#fff',
							borderRadius: '0.25rem',
						} }
					>
						<summary
							style={ { cursor: 'pointer', fontWeight: 600 } }
						>
							Error Details (for developers)
						</summary>
						<pre
							style={ {
								marginTop: '0.5rem',
								padding: '0.5rem',
								backgroundColor: '#f3f4f6',
								overflow: 'auto',
								fontSize: '0.875rem',
							} }
						>
							{ this.state.error && this.state.error.toString() }
							{ '\n\n' }
							{ this.state.errorInfo &&
								this.state.errorInfo.componentStack }
						</pre>
					</details>
					<button
						onClick={ this.handleReset }
						style={ {
							marginTop: '1rem',
							padding: '0.5rem 1rem',
							backgroundColor: '#ef4444',
							color: '#fff',
							border: 'none',
							borderRadius: '0.25rem',
							cursor: 'pointer',
							fontWeight: 600,
						} }
					>
						Try Again
					</button>
				</div>
			);
		}

		return this.props.children;
	}
}

ErrorBoundary.propTypes = {
	children: PropTypes.node.isRequired,
	fallback: PropTypes.node,
	errorMessage: PropTypes.string,
};

ErrorBoundary.defaultProps = {
	fallback: null,
	errorMessage: '',
};

export default ErrorBoundary;
