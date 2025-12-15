import { TabPanel } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const SettingsApp = () => {
    return (
        <div className="wrap">
            <h1>{__('Dynamic Services Settings', 'dynamic-online-services')}</h1>
            <TabPanel
                className="dynos-settings-tabs"
                activeClass="active-tab"
                tabs={[
                    {
                        name: 'general',
                        title: __('General', 'dynamic-online-services'),
                        className: 'tab-general',
                    },
                    {
                        name: 'hero',
                        title: __('Hero Section', 'dynamic-online-services'),
                        className: 'tab-hero',
                    },
                    {
                        name: 'cards',
                        title: __('Service Cards', 'dynamic-online-services'),
                        className: 'tab-cards',
                    },
                    {
                        name: 'faqs',
                        title: __('FAQs', 'dynamic-online-services'),
                        className: 'tab-faqs',
                    },
                    {
                        name: 'advanced',
                        title: __('Advanced', 'dynamic-online-services'),
                        className: 'tab-advanced',
                    },
                ]}
            >
                {(tab) => (
                    <div className="dynos-tab-content">
                        <h2>{tab.title}</h2>
                        <p> {__('This is the new React-based settings page.', 'dynamic-online-services')} </p>
                        <p> {__('Currently viewing:', 'dynamic-online-services')} {tab.title} </p>
                    </div>
                )}
            </TabPanel>
        </div>
    );
};

export default SettingsApp;
