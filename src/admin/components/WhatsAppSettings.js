import {
	PanelBody,
	SelectControl,
	TextareaControl,
	TextControl,
	Button,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import OptionControl from './OptionControl';

const WhatsAppSettings = ( { settings, onChange } ) => {
	const handleSelectChange = ( key, value ) => {
		onChange( {
			...settings,
			[ key ]: value,
		} );
	};

	return (
		<div className="dynos-tab-content">
			<h2>
				{ __( 'WhatsApp Configuration', 'dynamic-online-services' ) }
			</h2>

			<PanelBody
				title={ __( 'General Settings', 'dynamic-online-services' ) }
				initialOpen={ true }
			>
				<OptionControl
					label={ __(
						'Enable Floating Button',
						'dynamic-online-services'
					) }
					help={ __(
						'Display the WhatsApp button on the frontend.',
						'dynamic-online-services'
					) }
					type="toggle"
					optionKey="whatsapp_enabled"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __( 'Phone Number', 'dynamic-online-services' ) }
					help={ __(
						'Enter number with country code (e.g., 923001234567).',
						'dynamic-online-services'
					) }
					optionKey="whatsapp_number"
					settings={ settings }
					onChange={ onChange }
				/>
				<TextareaControl
					label={ __(
						'Pre-filled Message',
						'dynamic-online-services'
					) }
					help={ __(
						'Message to send when user clicks the button. Supports {current_page_url}, {page_title}.',
						'dynamic-online-services'
					) }
					value={ settings.whatsapp_message || '' }
					onChange={ ( value ) =>
						handleSelectChange( 'whatsapp_message', value )
					}
				/>
			</PanelBody>

			<PanelBody
				title={ __(
					'Appearance & Position',
					'dynamic-online-services'
				) }
				initialOpen={ false }
			>
				<div className="dynos-grid-2">
					<SelectControl
						label={ __( 'Icon Style', 'dynamic-online-services' ) }
						value={ settings.whatsapp_icon_style || 'default' }
						options={ [
							{
								label: __(
									'Official Logo',
									'dynamic-online-services'
								),
								value: 'default',
							},
							{
								label: __(
									'Chat Bubble',
									'dynamic-online-services'
								),
								value: 'chat',
							},
							{
								label: __(
									'User Avatar (Placeholder)',
									'dynamic-online-services'
								),
								value: 'avatar',
							},
						] }
						onChange={ ( value ) =>
							handleSelectChange( 'whatsapp_icon_style', value )
						}
					/>
					<SelectControl
						label={ __( 'Position', 'dynamic-online-services' ) }
						value={ settings.whatsapp_position || 'right' }
						options={ [
							{
								label: __(
									'Bottom Right',
									'dynamic-online-services'
								),
								value: 'right',
							},
							{
								label: __(
									'Bottom Left',
									'dynamic-online-services'
								),
								value: 'left',
							},
						] }
						onChange={ ( value ) =>
							handleSelectChange( 'whatsapp_position', value )
						}
					/>
				</div>

				<div className="dynos-grid-2">
					<OptionControl
						label={ __(
							'Horizontal Offset',
							'dynamic-online-services'
						) }
						help={ __(
							'From side edge (e.g., 20px).',
							'dynamic-online-services'
						) }
						type="text"
						optionKey="whatsapp_position_offset_x"
						settings={ settings }
						onChange={ onChange }
					/>
					<OptionControl
						label={ __(
							'Vertical Offset',
							'dynamic-online-services'
						) }
						help={ __(
							'From bottom edge (e.g., 20px).',
							'dynamic-online-services'
						) }
						type="text"
						optionKey="whatsapp_position_offset_y"
						settings={ settings }
						onChange={ onChange }
					/>
				</div>

				<OptionControl
					label={ __(
						'Background Color',
						'dynamic-online-services'
					) }
					type="color"
					optionKey="whatsapp_bg_color"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __( 'Icon Color', 'dynamic-online-services' ) }
					type="color"
					optionKey="whatsapp_icon_color"
					settings={ settings }
					onChange={ onChange }
				/>
			</PanelBody>

			<PanelBody
				title={ __(
					'Call to Action (CTA)',
					'dynamic-online-services'
				) }
				initialOpen={ false }
			>
				<OptionControl
					label={ __(
						'Enable CTA Bubble',
						'dynamic-online-services'
					) }
					help={ __(
						'Show a bubble next to the button.',
						'dynamic-online-services'
					) }
					type="toggle"
					optionKey="whatsapp_cta_enabled"
					settings={ settings }
					onChange={ onChange }
				/>

				{ settings.whatsapp_cta_enabled && (
					<>
						<OptionControl
							label={ __(
								'CTA Text',
								'dynamic-online-services'
							) }
							type="text"
							optionKey="whatsapp_cta_text"
							settings={ settings }
							onChange={ onChange }
						/>
						<OptionControl
							label={ __(
								'Delay (Seconds)',
								'dynamic-online-services'
							) }
							help={ __(
								'Delay before showing. 0 for immediate.',
								'dynamic-online-services'
							) }
							type="text"
							optionKey="whatsapp_cta_delay"
							settings={ settings }
							onChange={ onChange }
						/>
					</>
				) }
			</PanelBody>

			<PanelBody
				title={ __(
					'Visibility & Schedule',
					'dynamic-online-services'
				) }
				initialOpen={ false }
			>
				<div className="dynos-grid-2">
					<SelectControl
						label={ __(
							'Show On Pages',
							'dynamic-online-services'
						) }
						value={ settings.whatsapp_visibility || 'all' }
						options={ [
							{
								label: __(
									'All Pages',
									'dynamic-online-services'
								),
								value: 'all',
							},
							{
								label: __(
									'Home Page Only',
									'dynamic-online-services'
								),
								value: 'home',
							},
						] }
						onChange={ ( value ) =>
							handleSelectChange( 'whatsapp_visibility', value )
						}
					/>
					<OptionControl
						label={ __(
							'Enable Analytics',
							'dynamic-online-services'
						) }
						help={ __(
							'Fire GA/FB events.',
							'dynamic-online-services'
						) }
						type="toggle"
						optionKey="whatsapp_analytics_enabled"
						settings={ settings }
						onChange={ onChange }
					/>
				</div>

				<div className="dynos-grid-2">
					<OptionControl
						label={ __(
							'Show on Desktop',
							'dynamic-online-services'
						) }
						type="toggle"
						optionKey="whatsapp_show_desktop"
						settings={ settings }
						onChange={ onChange }
					/>
					<OptionControl
						label={ __(
							'Show on Mobile',
							'dynamic-online-services'
						) }
						type="toggle"
						optionKey="whatsapp_show_mobile"
						settings={ settings }
						onChange={ onChange }
					/>
				</div>

				<hr />

				<OptionControl
					label={ __( 'Enable Schedule', 'dynamic-online-services' ) }
					help={ __(
						'Limit button availability to specific hours.',
						'dynamic-online-services'
					) }
					type="toggle"
					optionKey="whatsapp_availability"
					settings={ settings }
					onChange={ onChange }
				/>

				{ settings.whatsapp_availability && (
					<>
						<div className="dynos-grid-2">
							<OptionControl
								label={ __(
									'Start Time',
									'dynamic-online-services'
								) }
								type="text"
								optionKey="whatsapp_schedule_start"
								settings={ settings }
								onChange={ onChange }
							/>
							<OptionControl
								label={ __(
									'End Time',
									'dynamic-online-services'
								) }
								type="text"
								optionKey="whatsapp_schedule_end"
								settings={ settings }
								onChange={ onChange }
							/>
						</div>

						<SelectControl
							label={ __(
								'Timezone',
								'dynamic-online-services'
							) }
							value={ settings.whatsapp_timezone || 'UTC' }
							options={
								window.dynosSettings?.timezones || [
									{ label: 'UTC', value: 'UTC' },
								]
							}
							onChange={ ( value ) =>
								handleSelectChange( 'whatsapp_timezone', value )
							}
							help={ __(
								'Select your timezone.',
								'dynamic-online-services'
							) }
						/>

						<hr />

						<SelectControl
							label={ __(
								'Offline Behavior',
								'dynamic-online-services'
							) }
							value={
								settings.whatsapp_offline_behavior || 'hide'
							}
							options={ [
								{
									label: __(
										'Hide Button',
										'dynamic-online-services'
									),
									value: 'hide',
								},
								{
									label: __(
										'Show Offline Message',
										'dynamic-online-services'
									),
									value: 'show',
								},
							] }
							onChange={ ( value ) =>
								handleSelectChange(
									'whatsapp_offline_behavior',
									value
								)
							}
							help={ __(
								'What to do when outside business hours.',
								'dynamic-online-services'
							) }
						/>

						{ settings.whatsapp_offline_behavior === 'show' && (
							<TextareaControl
								label={ __(
									'Offline Message',
									'dynamic-online-services'
								) }
								value={ settings.whatsapp_offline_text || '' }
								onChange={ ( value ) =>
									handleSelectChange(
										'whatsapp_offline_text',
										value
									)
								}
								help={ __(
									'Message to display when offline.',
									'dynamic-online-services'
								) }
							/>
						) }
					</>
				) }
			</PanelBody>

			<PanelBody
				title={ __( 'Multi-Agent Support', 'dynamic-online-services' ) }
				initialOpen={ false }
			>
				<OptionControl
					label={ __(
						'Enable Multiple Agents',
						'dynamic-online-services'
					) }
					help={ __(
						'Show a list of agents instead of direct chat.',
						'dynamic-online-services'
					) }
					type="toggle"
					optionKey="whatsapp_agents_enabled"
					settings={ settings }
					onChange={ onChange }
				/>

				{ settings.whatsapp_agents_enabled && (
					<div className="dynos-agents-list">
						{ ( settings.whatsapp_agents || [] ).map(
							( agent, index ) => (
								<div
									key={ index }
									className="dynos-agent-item"
									style={ {
										background: '#f8f9fa',
										padding: '15px',
										marginBottom: '15px',
										borderRadius: '4px',
										border: '1px solid #ddd',
									} }
								>
									<div
										style={ {
											display: 'flex',
											justifyContent: 'space-between',
											alignItems: 'center',
											marginBottom: '10px',
										} }
									>
										<h4 style={ { margin: 0 } }>
											{ __(
												`Agent ${ index + 1 }`,
												'dynamic-online-services'
											) }
										</h4>
										<Button
											isDestructive
											isSmall
											variant="secondary"
											onClick={ () => {
												const newAgents = [
													...( settings.whatsapp_agents ||
														[] ),
												];
												newAgents.splice( index, 1 );
												onChange( {
													...settings,
													whatsapp_agents: newAgents,
												} );
											} }
										>
											{ __(
												'Remove',
												'dynamic-online-services'
											) }
										</Button>
									</div>
									<div className="dynos-grid-2">
										<TextControl
											label={ __(
												'Name',
												'dynamic-online-services'
											) }
											value={ agent.name }
											onChange={ ( val ) => {
												const newAgents = [
													...( settings.whatsapp_agents ||
														[] ),
												];
												newAgents[ index ] = {
													...newAgents[ index ],
													name: val,
												};
												onChange( {
													...settings,
													whatsapp_agents: newAgents,
												} );
											} }
										/>
										<TextControl
											label={ __(
												'Number (e.g., 92300…)',
												'dynamic-online-services'
											) }
											value={ agent.number }
											onChange={ ( val ) => {
												const newAgents = [
													...( settings.whatsapp_agents ||
														[] ),
												];
												newAgents[ index ] = {
													...newAgents[ index ],
													number: val,
												};
												onChange( {
													...settings,
													whatsapp_agents: newAgents,
												} );
											} }
										/>
									</div>
									<TextControl
										label={ __(
											'Role / Label',
											'dynamic-online-services'
										) }
										value={ agent.label }
										placeholder="e.g. Sales Support"
										onChange={ ( val ) => {
											const newAgents = [
												...( settings.whatsapp_agents ||
													[] ),
											];
											newAgents[ index ] = {
												...newAgents[ index ],
												label: val,
											};
											onChange( {
												...settings,
												whatsapp_agents: newAgents,
											} );
										} }
									/>
									<TextControl
										label={ __(
											'Avatar URL (Optional)',
											'dynamic-online-services'
										) }
										value={ agent.avatar_url || '' }
										placeholder="https://example.com/avatar.jpg"
										onChange={ ( val ) => {
											const newAgents = [
												...( settings.whatsapp_agents ||
													[] ),
											];
											newAgents[ index ] = {
												...newAgents[ index ],
												avatar_url: val,
											};
											onChange( {
												...settings,
												whatsapp_agents: newAgents,
											} );
										} }
									/>
								</div>
							)
						) }
						<Button
							isSecondary
							onClick={ () => {
								const newAgents = [
									...( settings.whatsapp_agents || [] ),
									{
										name: '',
										number: '',
										label: '',
										avatar_url: '',
									},
								];
								onChange( {
									...settings,
									whatsapp_agents: newAgents,
								} );
							} }
						>
							{ __( 'Add New Agent', 'dynamic-online-services' ) }
						</Button>
					</div>
				) }
			</PanelBody>
		</div>
	);
};

export default WhatsAppSettings;
