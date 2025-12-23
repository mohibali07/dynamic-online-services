import { PanelBody, Button } from '@wordpress/components';
import { __, sprintf } from '@wordpress/i18n';
import OptionControl from './OptionControl';

const WhatsAppSettings = ( { settings, onChange } ) => {
	const handleAgentChange = ( index, agentUpdates ) => {
		const newAgents = [ ...( settings.whatsapp_agents || [] ) ];
		newAgents[ index ] = {
			...newAgents[ index ],
			...agentUpdates,
		};
		onChange( {
			...settings,
			whatsapp_agents: newAgents,
		} );
	};

	const removeAgent = ( index ) => {
		const newAgents = [ ...( settings.whatsapp_agents || [] ) ];
		newAgents.splice( index, 1 );
		onChange( {
			...settings,
			whatsapp_agents: newAgents,
		} );
	};

	const addAgent = () => {
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
					type="tel"
					optionKey="whatsapp_number"
					settings={ settings }
					onChange={ onChange }
				/>
				<OptionControl
					label={ __(
						'Pre-filled Message',
						'dynamic-online-services'
					) }
					help={ __(
						'Message to send when user clicks the button. Supports {current_page_url}, {page_title}.',
						'dynamic-online-services'
					) }
					type="textarea"
					optionKey="whatsapp_message"
					settings={ settings }
					onChange={ onChange }
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
					<OptionControl
						label={ __( 'Icon Style', 'dynamic-online-services' ) }
						type="select"
						optionKey="whatsapp_icon_style"
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
						settings={ settings }
						onChange={ onChange }
					/>
					<OptionControl
						label={ __( 'Position', 'dynamic-online-services' ) }
						type="select"
						optionKey="whatsapp_position"
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
						settings={ settings }
						onChange={ onChange }
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
							type="number"
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
					<OptionControl
						label={ __(
							'Show On Pages',
							'dynamic-online-services'
						) }
						type="select"
						optionKey="whatsapp_visibility"
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
						settings={ settings }
						onChange={ onChange }
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

				<div className="dynos-divider" />

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

						<OptionControl
							label={ __(
								'Timezone',
								'dynamic-online-services'
							) }
							type="select"
							optionKey="whatsapp_timezone"
							options={
								window.dynosSettings?.timezones || [
									{ label: 'UTC', value: 'UTC' },
								]
							}
							help={ __(
								'Select your timezone.',
								'dynamic-online-services'
							) }
							settings={ settings }
							onChange={ onChange }
						/>

						<div className="dynos-divider" />

						<OptionControl
							label={ __(
								'Offline Behavior',
								'dynamic-online-services'
							) }
							type="select"
							optionKey="whatsapp_offline_behavior"
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
							help={ __(
								'What to do when outside business hours.',
								'dynamic-online-services'
							) }
							settings={ settings }
							onChange={ onChange }
						/>

						{ settings.whatsapp_offline_behavior === 'show' && (
							<OptionControl
								label={ __(
									'Offline Message',
									'dynamic-online-services'
								) }
								type="textarea"
								optionKey="whatsapp_offline_text"
								help={ __(
									'Message to display when offline.',
									'dynamic-online-services'
								) }
								settings={ settings }
								onChange={ onChange }
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
								<div key={ index } className="dynos-agent-item">
									<div className="dynos-agent-header">
										<h4>
											{ sprintf(
												/* translators: %d is the agent number */
												__(
													'Agent %d',
													'dynamic-online-services'
												),
												index + 1
											) }
										</h4>
										<Button
											isDestructive
											isSmall
											variant="secondary"
											onClick={ () =>
												removeAgent( index )
											}
										>
											{ __(
												'Remove',
												'dynamic-online-services'
											) }
										</Button>
									</div>
									<div className="dynos-grid-2">
										<OptionControl
											label={ __(
												'Name',
												'dynamic-online-services'
											) }
											type="text"
											optionKey="name"
											settings={ agent }
											onChange={ ( val ) =>
												handleAgentChange( index, {
													name: val.name,
												} )
											}
										/>
										<OptionControl
											label={ __(
												'Number (e.g., 92300…)',
												'dynamic-online-services'
											) }
											type="tel"
											optionKey="number"
											settings={ agent }
											onChange={ ( val ) =>
												handleAgentChange( index, {
													number: val.number,
												} )
											}
										/>
									</div>
									<OptionControl
										label={ __(
											'Role / Label',
											'dynamic-online-services'
										) }
										type="text"
										optionKey="label"
										help={ __(
											'e.g. Sales Support',
											'dynamic-online-services'
										) }
										settings={ agent }
										onChange={ ( val ) =>
											handleAgentChange( index, {
												label: val.label,
											} )
										}
									/>
									<OptionControl
										label={ __(
											'Avatar URL (Optional)',
											'dynamic-online-services'
										) }
										type="text"
										optionKey="avatar_url"
										help={ __(
											'https://example.com/avatar.jpg',
											'dynamic-online-services'
										) }
										settings={ agent }
										onChange={ ( val ) =>
											handleAgentChange( index, {
												avatar_url: val.avatar_url,
											} )
										}
									/>
								</div>
							)
						) }
						<Button isSecondary onClick={ addAgent }>
							{ __( 'Add New Agent', 'dynamic-online-services' ) }
						</Button>
					</div>
				) }
			</PanelBody>
		</div>
	);
};

export default WhatsAppSettings;
