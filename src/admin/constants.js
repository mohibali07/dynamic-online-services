/**
 * Dynamic Online Services - Admin Constants
 * Centralized constants for better maintainability and type safety
 */

/**
 * Tab IDs for admin settings interface
 */
export const TABS = {
	GENERAL: 'general',
	HERO: 'hero',
	CARDS: 'cards',
	FAQS: 'faqs',
	WHATSAPP: 'whatsapp',
	MARKETING: 'marketing',
};

/**
 * Keyboard shortcuts
 */
export const KEYBOARD_SHORTCUTS = {
	SAVE: 's', // Will be used with Ctrl/Cmd modifier
};

/**
 * Color presets for color picker
 */
export const COLOR_PRESETS = [
	{ name: 'Black', color: '#000000' },
	{ name: 'White', color: '#ffffff' },
	{ name: 'Slate 900', color: '#0f172a' },
	{ name: 'Slate 600', color: '#475569' },
	{ name: 'Slate 400', color: '#94a3b8' },
	{ name: 'Indigo 600', color: '#4f46e5' },
	{ name: 'Indigo 500', color: '#6366f1' },
	{ name: 'Rose 600', color: '#e11d48' },
	{ name: 'Rose 500', color: '#f43f5e' },
	{ name: 'Green 500', color: '#10b981' },
	{ name: 'Blue 500', color: '#3b82f6' },
	{ name: 'Red 500', color: '#ef4444' },
	{ name: 'Amber 500', color: '#f59e0b' },
];

/**
 * Semantic color tokens (matching CSS custom properties)
 */
export const SEMANTIC_COLORS = {
	PRIMARY: '#4f46e5',
	PRIMARY_HOVER: '#4338ca',
	ACCENT: '#e11d48',
	SUCCESS: '#10b981',
	WARNING: '#f59e0b',
	DANGER: '#ef4444',
};

/**
 * Animation durations (in milliseconds)
 */
export const ANIMATION_DURATION = {
	FAST: 150,
	NORMAL: 200,
	SLOW: 350,
	SUCCESS_ANIMATION: 2000,
};

/**
 * Breakpoints (should match CSS)
 */
export const BREAKPOINTS = {
	MOBILE: 600,
	TABLET: 960,
	DESKTOP: 1400,
};
