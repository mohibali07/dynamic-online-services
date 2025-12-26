import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import OptionControl from './OptionControl';

const MarketingSettings = ( { settings, onChange } ) => {
	return (
		<div className="dynos-tab-content">
			<h2>
				{ __( 'Marketing Widget Settings', 'dynamic-online-services' ) }
			</h2>

			<PanelBody
				title={ __( 'Matching Engine', 'dynamic-online-services' ) }
				initialOpen={ true }
			>
				<OptionControl
					label={ __( 'Match Threshold', 'dynamic-online-services' ) }
					help={ __(
						'Minimum score required for a service to match. Higher = stricter matches.',
						'dynamic-online-services'
					) }
					type="number"
					optionKey="marketing_match_threshold"
					settings={ settings }
					onChange={ onChange }
					min={ 1 }
					max={ 50 }
				/>
				<OptionControl
					label={ __( 'Stop Words', 'dynamic-online-services' ) }
					help={ __(
						'Comma-separated list of words to ignore during keyword matching.',
						'dynamic-online-services'
					) }
					type="textarea"
					optionKey="marketing_stop_words"
					settings={ settings }
					onChange={ onChange }
					rows={ 3 }
				/>
			</PanelBody>
		</div>
	);
};

export default MarketingSettings;
