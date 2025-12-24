=== Dynamic Online Services ===
Contributors: techmiresolutions
Donate link: http://aabtaab.com/
Tags: courses, online, education, custom, post, type, taxonomy, shortcode, settings
Requires at least: 6.7
Requires PHP: 8.3
Tested up to: 6.7
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A custom plugin for managing Dynamic Online Services and their categories with a combined, sortable selection field and customizable styles.

== Description ==

This plugin provides a custom post type for "Courses" and a custom taxonomy for "Course Categories". It also provides shortcodes for displaying courses and categories on the front-end, and a settings page for customizing the appearance of the front-end components.

== Features ==

* Custom Post Type: Courses
* Custom Taxonomy: Course Categories (hierarchical)
* FAQ Management: Add FAQs to individual courses
* Hero Sections: Customizable hero sections for categories and courses
* Course Cards: Display categories and courses in a grid layout
* Customizable Styles: Color and font customization through settings page
* Shortcodes: Multiple shortcodes for flexible content display
* REST API Support: Full REST API support for custom post types and taxonomies
* Version: 1.1.0
* Requires: PHP 8.3+, WordPress 6.7+
* Architecture: Follows modern WordPress plugin standards with PSR-4 autoloading, strict typing, and class-based structure (`includes/` directory).
* Extensible: Multiple filters and hooks for developers
* Security: Follows WordPress security best practices
* Performance: Optimized script and style loading

== Installation ==

1. Upload the `dynamic-online-services` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.

== Frequently Asked Questions ==

= How do I use the shortcodes? =

You can use the following shortcodes to display courses and categories on the front-end:

* `[service_faqs_accordion]`
* `[service_category_content]`
* `[service_category_hero]`
* `[single_service_hero]`

== Screenshots ==

1. The "Courses" custom post type in the WordPress admin.
2. The "Course Categories" custom taxonomy in the WordPress admin.
3. The settings page for the plugin.

== Changelog ==

= 1.1.0 =
* Initial release.
* Added custom post type for Courses
* Added hierarchical taxonomy for Course Categories
* Implemented FAQ management system
* Added hero section shortcodes
* Added course card display shortcode
* Created settings page for customization
* Added proper internationalization support
* Implemented security best practices
* Added extensibility hooks and filters
* Optimized performance with conditional asset loading
