<?php
/**
 * Hooks and Filters Documentation
 *
 * This file documents all available hooks and filters in the plugin.
 * This file is for documentation purposes only and is not loaded.
 *
 * @package Dynamic_Online_Services
 * @subpackage Documentation
 */

declare(strict_types=1);

/**
 * FILTERS
 */

/**
 * Filter: dynos_get_options
 *
 * Allows filtering of all plugin options before they are returned.
 *
 * @since 1.1.0
 * @param array $options The complete options array.
 * @return array Modified options array.
 */

/**
 * Filter: dynos_get_option
 *
 * Allows filtering of individual option values.
 *
 * @since 1.1.0
 * @param mixed  $value   The option value.
 * @param string $key     The option key.
 * @param mixed  $default The default value.
 * @param array  $options The complete options array.
 * @return mixed Modified option value.
 */

/**
 * Filter: dynos_service_cpt_labels
 *
 * Allows filtering of custom post type labels before registration.
 *
 * @since 1.1.0
 * @param array $labels Array of labels for the service post type.
 * @return array Modified labels array.
 */

/**
 * Filter: dynos_service_cpt_args
 *
 * Allows filtering of custom post type arguments before registration.
 *
 * @since 1.1.0
 * @param array $args Array of arguments for the service post type.
 * @return array Modified arguments array.
 */

/**
 * Filter: dynos_services_category_tax_labels
 *
 * Allows filtering of taxonomy labels before registration.
 *
 * @since 1.1.0
 * @param array $labels Array of labels for the services_category taxonomy.
 * @return array Modified labels array.
 */

/**
 * Filter: dynos_services_category_tax_args
 *
 * Allows filtering of taxonomy arguments before registration.
 *
 * @since 1.1.0
 * @param array $args Array of arguments for the services_category taxonomy.
 * @return array Modified arguments array.
 */

/**
 * Filter: dynos_service_permalink_before_process
 *
 * Allows filtering of service permalink before category replacement.
 *
 * @since 1.1.0
 * @param string  $post_link The post permalink.
 * @param WP_Post $post      The post object.
 * @return string Modified permalink.
 */

/**
 * Filter: dynos_service_permalink_after_process
 *
 * Allows filtering of service permalink after category replacement.
 *
 * @since 1.1.0
 * @param string  $post_link The post permalink.
 * @param WP_Post $post      The post object.
 * @param string  $term_path The category path used in the permalink.
 * @return string Modified permalink.
 */

/**
 * Filter: dynos_before_save_faqs
 *
 * Allows filtering of FAQs before they are saved to the database.
 *
 * @since 1.1.0
 * @param array $faqs    Array of FAQ items.
 * @param int   $post_id The post ID.
 * @return array Modified FAQs array.
 */

/**
 * Filter: dynos_before_save_category_thumbnail
 *
 * Allows filtering of category thumbnail ID before saving.
 *
 * @since 1.1.0
 * @param int $thumbnail_id The attachment ID.
 * @param int $term_id       The term ID.
 * @return int Modified thumbnail ID.
 */

/**
 * Filter: dynos_placeholder_image_url
 *
 * Allows filtering of placeholder image URL.
 *
 * @since 1.1.0
 * @param string $url  The placeholder URL (empty by default).
 * @param string $size The image size requested.
 * @return string Modified placeholder URL.
 */

/**
 * Filter: dynos_google_font_family
 *
 * Allows filtering of Google Font family name before enqueuing.
 *
 * @since 1.1.0
 * @param string $font_family The font family name.
 * @param string $handle      The style handle.
 * @return string Modified font family name.
 */

/**
 * Filter: dynos_google_font_url
 *
 * Allows filtering of Google Fonts URL.
 *
 * @since 1.1.0
 * @param string $url              The Google Fonts URL.
 * @param string $font_family      The font family name.
 * @param string $font_family_encoded The encoded font family name.
 * @return string Modified Google Fonts URL.
 */

/**
 * Filter: dynos_hero_overlay_color
 *
 * Allows filtering of hero overlay color.
 *
 * @since 1.1.0
 * @param string $overlay_color The overlay color.
 * @return string Modified overlay color.
 */

/**
 * Filter: dynos_hero_dynamic_css
 *
 * Allows filtering of hero section dynamic CSS before output.
 *
 * @since 1.1.0
 * @param string $css           The CSS string.
 * @param string $overlay_color The overlay color.
 * @param array  $options       The options array.
 * @return string Modified CSS string.
 */

/**
 * Filter: dynos_category_hero_term
 *
 * Allows filtering of the queried term object for category hero.
 *
 * @since 1.1.0
 * @param WP_Term $queried_object The term object.
 * @return WP_Term Modified term object.
 */

/**
 * Filter: dynos_category_hero_title_color
 *
 * Allows filtering of category hero title color.
 *
 * @since 1.1.0
 * @param string  $title_color    The title color.
 * @param WP_Term $queried_object The term object.
 * @return string Modified title color.
 */

/**
 * Filter: dynos_category_hero_font_family
 *
 * Allows filtering of category hero font family.
 *
 * @since 1.1.0
 * @param string  $font_family    The font family.
 * @param WP_Term $queried_object The term object.
 * @return string Modified font family.
 */

/**
 * Filter: dynos_category_hero_image_url
 *
 * Allows filtering of category hero image URL.
 *
 * @since 1.1.0
 * @param string  $image_url      The image URL.
 * @param WP_Term $queried_object The term object.
 * @return string Modified image URL.
 */

/**
 * Filter: dynos_category_hero_height
 *
 * Allows filtering of category hero height.
 *
 * @since 1.1.0
 * @param string  $height         The height value.
 * @param WP_Term $queried_object The term object.
 * @return string Modified height value.
 */

/**
 * Filter: dynos_category_hero_title
 *
 * Allows filtering of category hero title.
 *
 * @since 1.1.0
 * @param string  $title          The title.
 * @param WP_Term $queried_object The term object.
 * @return string Modified title.
 */

/**
 * Filter: dynos_category_hero_output
 *
 * Allows filtering of category hero HTML output.
 *
 * @since 1.1.0
 * @param string  $output         The HTML output.
 * @param WP_Term $queried_object The term object.
 * @return string Modified HTML output.
 */

/**
 * Filter: dynos_service_hero_title_color
 *
 * Allows filtering of service hero title color.
 *
 * @since 1.1.0
 * @param string  $title_color The title color.
 * @param WP_Post $post       The post object.
 * @return string Modified title color.
 */

/**
 * Filter: dynos_service_hero_font_family
 *
 * Allows filtering of service hero font family.
 *
 * @since 1.1.0
 * @param string  $font_family The font family.
 * @param WP_Post $post        The post object.
 * @return string Modified font family.
 */

/**
 * Filter: dynos_service_hero_image_url
 *
 * Allows filtering of service hero image URL.
 *
 * @since 1.1.0
 * @param string  $image_url The image URL.
 * @param WP_Post $post     The post object.
 * @return string Modified image URL.
 */

/**
 * Filter: dynos_service_hero_height
 *
 * Allows filtering of service hero height.
 *
 * @since 1.1.0
 * @param string  $height The height value.
 * @param WP_Post $post   The post object.
 * @return string Modified height value.
 */

/**
 * Filter: dynos_service_hero_title
 *
 * Allows filtering of service hero title.
 *
 * @since 1.1.0
 * @param string  $post_title The post title.
 * @param WP_Post $post       The post object.
 * @return string Modified title.
 */

/**
 * Filter: dynos_service_hero_description
 *
 * Allows filtering of service hero description.
 *
 * @since 1.1.0
 * @param string  $description The description.
 * @param WP_Post $post       The post object.
 * @return string Modified description.
 */

/**
 * Filter: dynos_service_hero_output
 *
 * Allows filtering of service hero HTML output.
 *
 * @since 1.1.0
 * @param string  $output The HTML output.
 * @param WP_Post $post   The post object.
 * @return string Modified HTML output.
 */

/**
 * Filter: dynos_faqs_empty_content
 *
 * Allows filtering of content displayed when no FAQs are found.
 *
 * @since 1.1.0
 * @param string $empty_content The empty content (empty by default).
 * @param int    $post_id       The post ID.
 * @return string Modified empty content.
 */

/**
 * Filter: dynos_faqs_before_display
 *
 * Allows filtering of FAQs array before display.
 *
 * @since 1.1.0
 * @param array $faqs    Array of FAQ items.
 * @param int   $post_id The post ID.
 * @return array Modified FAQs array.
 */

/**
 * Filter: dynos_faqs_title
 *
 * Allows filtering of FAQ section title.
 *
 * @since 1.1.0
 * @param string $title   The title.
 * @param int    $post_id The post ID.
 * @return string Modified title.
 */

/**
 * Filter: dynos_faq_item
 *
 * Allows filtering of individual FAQ item before display.
 *
 * @since 1.1.0
 * @param array $faq_item Array with 'question' and 'answer' keys.
 * @param int   $index    The FAQ index.
 * @param int   $post_id  The post ID.
 * @return array Modified FAQ item array.
 */

/**
 * Filter: dynos_faqs_output
 *
 * Allows filtering of FAQ accordion HTML output.
 *
 * @since 1.1.0
 * @param string $output  The HTML output.
 * @param array  $faqs    Array of FAQ items.
 * @param int    $post_id The post ID.
 * @return string Modified HTML output.
 */

/**
 * Filter: dynos_faq_dynamic_css
 *
 * Allows filtering of FAQ accordion dynamic CSS before output.
 *
 * @since 1.1.0
 * @param string $css     The CSS string.
 * @param array  $options The options array.
 * @return string Modified CSS string.
 */

/**
 * Filter: dynos_category_content_term
 *
 * Allows filtering of the term object for category content shortcode.
 *
 * @since 1.1.0
 * @param WP_Term $term The term object.
 * @return WP_Term Modified term object.
 */

/**
 * Filter: dynos_category_content_services
 *
 * Allows filtering of services array for category content shortcode.
 *
 * @since 1.1.0
 * @param array   $services Array of WP_Post objects.
 * @param WP_Term $term     The term object.
 * @return array Modified services array.
 */

/**
 * Filter: dynos_category_content_items
 *
 * Allows filtering of items array (categories and services) before display.
 *
 * @since 1.1.0
 * @param array   $items Array of items to display.
 * @param WP_Term $term  The term object.
 * @return array Modified items array.
 */

/**
 * Filter: dynos_category_content_grid_class
 *
 * Allows filtering of grid container CSS class.
 *
 * @since 1.1.0
 * @param string  $grid_class The CSS class.
 * @param WP_Term $term       The term object.
 * @return string Modified CSS class.
 */

/**
 * Filter: dynos_category_content_item
 *
 * Allows filtering of individual item before rendering.
 *
 * @since 1.1.0
 * @param array   $item  The item array.
 * @param int     $index The item index.
 * @param WP_Term $term  The term object.
 * @return array Modified item array.
 */

/**
 * Filter: dynos_category_content_card_class
 *
 * Allows filtering of card CSS class.
 *
 * @since 1.1.0
 * @param string  $card_class The CSS class.
 * @param array   $item       The item array.
 * @param int     $index      The item index.
 * @return string Modified CSS class.
 */

/**
 * Filter: dynos_category_content_button_text
 *
 * Allows filtering of card button text.
 *
 * @since 1.1.0
 * @param string $button_text The button text.
 * @param array  $item        The item array.
 * @return string Modified button text.
 */

/**
 * Filter: dynos_category_content_empty_message
 *
 * Allows filtering of empty state message.
 *
 * @since 1.1.0
 * @param string  $empty_message The empty message.
 * @param WP_Term $term          The term object.
 * @return string Modified empty message.
 */

/**
 * Filter: dynos_category_content_output
 *
 * Allows filtering of category content HTML output.
 *
 * @since 1.1.0
 * @param string  $output         The HTML output.
 * @param array   $items_to_display Array of items.
 * @param WP_Term $term           The term object.
 * @return string Modified HTML output.
 */

/**
 * Filter: dynos_should_load_card_styles
 *
 * Allows filtering of whether to load card styles.
 *
 * @since 1.1.0
 * @param bool    $load_styles Whether to load styles.
 * @param WP_Post $post       The post object (may be null).
 * @return bool Modified load styles flag.
 */

/**
 * Filter: dynos_card_bg_color
 *
 * Allows filtering of card background color.
 *
 * @since 1.1.0
 * @param string $card_bg The background color.
 * @return string Modified background color.
 */

/**
 * Filter: dynos_card_title_color
 *
 * Allows filtering of card title color.
 *
 * @since 1.1.0
 * @param string $card_title The title color.
 * @return string Modified title color.
 */

/**
 * Filter: dynos_card_description_color
 *
 * Allows filtering of card description color.
 *
 * @since 1.1.0
 * @param string $card_desc The description color.
 * @return string Modified description color.
 */

/**
 * Filter: dynos_card_button_bg_color
 *
 * Allows filtering of card button background color.
 *
 * @since 1.1.0
 * @param string $card_btn_bg The button background color.
 * @return string Modified button background color.
 */

/**
 * Filter: dynos_card_button_text_color
 *
 * Allows filtering of card button text color.
 *
 * @since 1.1.0
 * @param string $card_btn_text The button text color.
 * @return string Modified button text color.
 */

/**
 * Filter: dynos_card_font_family
 *
 * Allows filtering of card font family.
 *
 * @since 1.1.0
 * @param string $card_font The font family.
 * @return string Modified font family.
 */

/**
 * Filter: dynos_card_title_hover_color
 *
 * Allows filtering of card title hover color.
 *
 * @since 1.1.0
 * @param string $card_title_hover_color The hover color (default: #ffffff).
 * @param array  $options                The options array.
 * @return string Modified hover color.
 */

/**
 * Filter: dynos_card_dynamic_css
 *
 * Allows filtering of card dynamic CSS before output.
 *
 * @since 1.1.0
 * @param string $css     The CSS string.
 * @param array  $options The options array.
 * @return string Modified CSS string.
 */

/**
 * Filter: dynos_service_cards_query_args
 *
 * Allows filtering of arguments for the service cards query.
 *
 * @since 1.1.0
 * @param array $args The query arguments.
 * @param array $atts The shortcode attributes.
 * @return array Modified query arguments.
 */

/**
 * Filter: dynos_category_content_query_args
 *
 * Allows filtering of arguments for the category content query.
 *
 * @since 1.1.0
 * @param array   $query_args The query arguments.
 * @param WP_Term $term       The term object.
 * @return array Modified query arguments.
 */

/**
 * ACTIONS
 */

/**
 * Action: dynos_after_save_faqs
 *
 * Fired after FAQs are saved to the database.
 *
 * @since 1.1.0
 * @param int   $post_id The post ID.
 * @param array $new_faqs The saved FAQs array.
 */

/**
 * Action: dynos_after_save_category_thumbnail
 *
 * Fired after category thumbnail is saved.
 *
 * @since 1.1.0
 * @param int $term_id       The term ID.
 * @param int $thumbnail_id The attachment ID.
 */

/**
 * Action: dynos_permalink_term_error
 *
 * Fired when generating a permalink if no valid terms are found.
 *
 * @since 1.1.0
 * @param mixed $terms   The terms result (WP_Error or false/empty element).
 * @param int   $post_id The post ID.
 */

/**
 * Action: dynos_permalink_self_reference
 *
 * Fired when a circular reference to itself is detected in permalink generation.
 *
 * @since 1.1.0
 * @param int $term_id The problematic term ID.
 */

/**
 * Action: dynos_permalink_circular_reference
 *
 * Fired when a circular reference loop is detected in permalink generation.
 *
 * @since 1.1.0
 * @param int   $current_term_id The current term ID.
 * @param int   $parent_id       The parent term ID being checked.
 * @param array $visited_ids     Array of visited term IDs.
 */

/**
 * Action: dynos_permalink_parent_term_error
 *
 * Fired when a parent term cannot be retrieved during permalink generation.
 *
 * @since 1.1.0
 * @param mixed $parent_term The parent term result.
 * @param int   $parent_id   The parent term ID.
 */

/**
 * Action: dynos_permalink_max_depth_reached
 *
 * Fired when max taxonomy depth is reached during permalink generation.
 *
 * @since 1.1.0
 * @param int $term_id   The current term ID.
 * @param int $max_depth The maximum allowed depth.
 */

/**
 * Action: dynos_rate_limit_exceeded
 *
 * Fired when a user exceeds the rate limit.
 *
 * @since 1.1.0
 * @param string $action       The action being limited.
 * @param int    $user_id      The user ID.
 * @param int    $attempts     The number of attempts.
 * @param int    $max_attempts The maximum allowed attempts.
 */

/**
 * Action: dynos_rate_limits_cleared
 *
 * Fired when rate limits are cleared.
 *
 * @since 1.1.0
 */

/**
 * Action: dynos_faq_limit_exceeded
 *
 * Fired when the number of FAQs exceeds the allowed limit.
 *
 * @since 1.1.0
 * @param int $post_id  The post ID.
 * @param int $max_faqs The maximum allowed FAQs.
 * @param int $count    The current count of FAQs.
 */

/**
 * Action: dynos_faq_count_mismatch
 *
 * Fired when FAQ question and answer counts do not match during save.
 *
 * @since 1.1.0
 * @param int $post_id       The post ID.
 * @param int $questions_count The count of questions.
 * @param int $answers_count   The count of answers.
 */

/**
 * Action: dynos_category_content_term_error
 *
 * Fired when the queried object is not a valid term in the category content shortcode.
 *
 * @since 1.1.0
 * @param mixed $queried_object The invalid queried object.
 */

/**
 * Action: dynos_category_content_term_link_error
 *
 * Fired when a term link cannot be generated in the category content shortcode.
 *
 * @since 1.1.0
 * @param WP_Term $category The category term.
 * @param mixed   $term_link The error result.
 */

/**
 * Action: dynos_category_hero_term_error
 *
 * Fired when the queried object is not a valid term in the category hero shortcode.
 *
 * @since 1.1.0
 * @param mixed $queried_object The invalid queried object.
 */

/**
 * Action: dynos_pagination_term_link_error
 *
 * Fired when the base term link cannot be generated for pagination.
 *
 * @since 1.1.0
 * @param WP_Term $queried_object The term object.
 * @param mixed   $base_term_link The error result.
 */

/**
 * Action: dynos_constants_cache_reset
 *
 * Fired when the constants cache is forcibly reset.
 *
 * @since 1.1.0
 */

/**
 * Action: dynos_exception_handled
 *
 * Fired when an exception is caught and handled by the error handler.
 *
 * @since 1.1.0
 * @param Throwable $exception The caught exception.
 */

/**
 * Action: dynos_activation_error
 *
 * Fired when an error occurs during plugin activation.
 *
 * @since 1.1.0
 * @param Throwable $exception The caught exception.
 */

/**
 * Action: dynos_log_error
 *
 * Fired to log an error message (usually for admin notices or debugging).
 *
 * @since 1.1.0
 * @param string $message The error message.
 * @param string $level   The error level (e.g., 'error', 'warning').
 * @param array  $context Additional context.
 */

/**
 * Action: dynos_breakpoints_loaded
 *
 * Fired when CSS breakpoints are loaded.
 *
 * @since 1.1.0
 * @param string $tablet The tablet breakpoint.
 * @param string $mobile The mobile breakpoint.
 */

/**
 * Action: dynos_constants_cache_cleared
 *
 * Fired when the constants cache is cleared.
 *
 * @since 1.1.0
 */

/**
 * Action: dynos_options_cache_invalidated
 *
 * Fired when the options cache is invalidated (e.g., on save).
 *
 * @since 1.1.0
 */
