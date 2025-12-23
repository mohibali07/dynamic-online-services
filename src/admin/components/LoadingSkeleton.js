import { Dashicon } from '@wordpress/components';

/**
 * Loading Skeleton Component
 * Displays a premium skeleton loader while content is loading
 */
const LoadingSkeleton = () => {
	return (
		<div className="dynos-settings-wrap">
			<div className="dynos-app-container">
				{ /* Sidebar Skeleton */ }
				<aside className="dynos-sidebar">
					<div className="dynos-sidebar-header">
						<h1>
							<Dashicon icon="superhero-alt" />
							<span>DynOS</span>
						</h1>
					</div>

					<nav className="dynos-sidebar-nav">
						{ [ 1, 2, 3, 4, 5 ].map( ( i ) => (
							<div
								key={ i }
								className="dynos-skeleton dynos-skeleton-sidebar"
							/>
						) ) }
					</nav>

					<div className="dynos-sidebar-footer">
						<div className="dynos-skeleton dynos-skeleton-sidebar" />
					</div>
				</aside>

				{ /* Main Content Skeleton */ }
				<main className="dynos-main-content">
					<div className="dynos-content-header">
						<div
							className="dynos-skeleton"
							style={ {
								width: '200px',
								height: '36px',
								marginBottom: '12px',
							} }
						/>
						<div
							className="dynos-skeleton"
							style={ {
								width: '400px',
								height: '24px',
							} }
						/>
					</div>

					<div className="dynos-panel-wrapper">
						{ [ 1, 2 ].map( ( i ) => (
							<div
								key={ i }
								className="dynos-skeleton dynos-skeleton-panel"
							/>
						) ) }
					</div>
				</main>
			</div>
		</div>
	);
};

export default LoadingSkeleton;
