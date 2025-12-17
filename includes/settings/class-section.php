<?php
/**
 * Settings Section Handler
 *
 * Generic handler for registering settings sections and their fields.
 *
 * @package Dynamic_Online_Services
 * @subpackage Settings
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Settings;

if ( ! \defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Section
 */
class Section {

	/**
	 * Register a settings section and its fields.
	 *
	 * @since 1.1.3
	 * @param string $section_key The key of the section in the Config map.
	 * @return void
	 */
	public static function register( string $section_key ): void {
		$map = Config::get_map();

		if ( ! isset( $map[ $section_key ] ) ) {
			return;
		}

		$section = $map[ $section_key ];
		$section_id = $section['id'];
		$page = 'Dynamic_Online_Services'; // Plugin setting page slug

		// Section registration is done in Registration class or can be done here.
		// Registration class currently does it. We just need to register fields.
		// Wait, Registration class calls add_settings_section.
		// So we just iterate fields.

		if ( isset( $section['fields'] ) && \is_array( $section['fields'] ) ) {
			foreach ( $section['fields'] as $field_key => $field ) {
				$field_id = $field_key; // or prefix with dynos_
				$title = $field['title'];

				// Resolve callback. Config uses string 'dynos_text_field_callback'.
				// We should map this to FieldRenderers class if possible, or use the global function if exists.
				$callback = $field['callback'];

				// Handle legacy string callbacks if we want to support the existing Config without changes
				if ( \is_string( $callback ) && \strpos( $callback, 'dynos_' ) === 0 ) {
					// Map dynos_xyz_callback to field renderers if we can, or just let WP handle it if the function exists.
                    // But we want to use Class methods.
                    // Let's assume global functions exist for now, or check generic mapping.
				}

                // Better approach: map known callbacks to Class methods
                $class_callback = self::map_callback($callback);

				$args = isset( $field['args'] ) ? $field['args'] : [];
				// Pass label_for for accessibility
				$args['label_for'] = $field_key;
                // Field ID for get_option
                $args['field_id'] = $field_key;
                // Name for input name attribute
                $args['name'] = $field_key;

				\add_settings_field(
					$field_key,
					$title,
					$class_callback,
					$page,
					$section_id,
					$args
				);
			}
		}
	}

    /**
     * Map string callbacks to FieldRenderers methods.
     *
     * @param mixed $callback
     * @return callable
     */
    private static function map_callback($callback) {
        if (!is_string($callback)) {
            return $callback;
        }

        switch ($callback) {
            case 'dynos_text_field_callback':
                return [\TechmireSolutions\DynamicOnlineServices\Settings\FieldRenderers::class, 'text_field_callback'];
            case 'dynos_number_field_callback':
                return [\TechmireSolutions\DynamicOnlineServices\Settings\FieldRenderers::class, 'number_field_callback'];
            case 'dynos_checkbox_field_callback':
                return [\TechmireSolutions\DynamicOnlineServices\Settings\FieldRenderers::class, 'checkbox_field_callback'];
            case 'dynos_color_field_callback':
                return [\TechmireSolutions\DynamicOnlineServices\Settings\FieldRenderers::class, 'color_field_callback'];
            case 'dynos_textarea_field_callback':
                return [\TechmireSolutions\DynamicOnlineServices\Settings\FieldRenderers::class, 'textarea_field_callback'];
            case 'dynos_select_field_callback':
                return [\TechmireSolutions\DynamicOnlineServices\Settings\FieldRenderers::class, 'select_field_callback'];
            case 'dynos_radio_field_callback':
                return [\TechmireSolutions\DynamicOnlineServices\Settings\FieldRenderers::class, 'radio_field_callback'];
            case 'dynos_font_family_field_callback':
                return [\TechmireSolutions\DynamicOnlineServices\Settings\FieldRenderers::class, 'font_family_field_callback'];
            default:
                return $callback;
        }
    }
}
