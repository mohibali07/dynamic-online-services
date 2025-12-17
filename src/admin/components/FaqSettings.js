import { PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import OptionControl from './OptionControl';

/**
 * FaqSettings Component.
 *
 * Renders FAQ accordion styling settings including colors and dimensions.
 *
 * @since 1.0.0
 *
 * @param {Object}   props          Component props.
 * @param {Object}   props.settings Current settings object.
 * @param {Function} props.onChange Callback to update settings.
 *
 * @return {JSX.Element} The FaqSettings component.
 */
const FaqSettings = ({ settings, onChange }) => {
	return (
		<div className="dynos-tab-content">
			<h2>
				{__('FAQ Accordion Settings', 'dynamic-online-services')}
			</h2>

			<PanelBody
				title={__('Colors', 'dynamic-online-services')}
				initialOpen={true}
			>
				<OptionControl
					label={__(
						'FAQ Item Border Color',
						'dynamic-online-services'
					)}
					type="color"
					optionKey="faq_item_border_color"
					settings={settings}
					onChange={onChange}
				/>
				<OptionControl
					label={__(
						'FAQ Question Background',
						'dynamic-online-services'
					)}
					type="color"
					optionKey="faq_question_bg_color"
					settings={settings}
					onChange={onChange}
				/>
				<OptionControl
					label={__(
						'FAQ Answer Text Color',
						'dynamic-online-services'
					)}
					type="color"
					optionKey="faq_answer_text_color"
					settings={settings}
					onChange={onChange}
				/>
			</PanelBody>

			<PanelBody
				title={__('Dimensions', 'dynamic-online-services')}
				initialOpen={false}
			>
				<OptionControl
					label={__('Border Radius', 'dynamic-online-services')}
					help={__(
						'Rounding of the FAQ item corners.',
						'dynamic-online-services'
					)}
					optionKey="faq_item_border_radius"
					settings={settings}
					onChange={onChange}
				/>
				<OptionControl
					label={__('Margin Bottom', 'dynamic-online-services')}
					help={__(
						'Space between FAQ items.',
						'dynamic-online-services'
					)}
					optionKey="faq_item_margin_bottom"
					settings={settings}
					onChange={onChange}
				/>
			</PanelBody>
		</div>
	);
};

export default FaqSettings;
