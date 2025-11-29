<?php
/**
 * Error Handler
 *
 * Centralized error handling for the plugin.
 *
 * @package Dynamic_Online_Services
 * @subpackage Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle plugin exceptions gracefully.
 *
 * Replaces direct wp_die() calls with proper exception handling.
 *
 * @since 1.1.0
 * @param DOC_Exception $exception Exception object.
 * @param bool $log_error Whether to log the error. Default true.
 * @return void
 */
function doc_handle_exception(DOC_Exception $exception, bool $log_error = true): void {
    if ($log_error) {
        // Log error using centralized logging function
        if (function_exists('doc_log_error')) {
            doc_log_error(
                $exception->getMessage(),
                'error',
                array_merge(
                    $exception->get_context(),
                    array(
                        'error_code' => $exception->get_error_code(),
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                    )
                )
            );
        }
    }

    // Fire action for extensibility
    do_action('doc_exception_handled', $exception);

    // In admin context, display error notice instead of wp_die
    if (is_admin()) {
        // Add admin notice
        if (function_exists('doc_add_validation_warning_notice')) {
            $message = sprintf(
                /* translators: 1: Error message */
                esc_html__('Error: %s', 'dynamic-online-services'),
                $exception->getMessage()
            );
            doc_add_validation_warning_notice($message, $exception->getMessage());
        }

        // Only use wp_die as last resort for critical errors
        if (500 === $exception->get_http_status_code()) {
            wp_die(
                esc_html($exception->getMessage()),
                esc_html__('Plugin Error', 'dynamic-online-services'),
                array(
                    'response' => $exception->get_http_status_code(),
                    'back_link' => true,
                )
            );
        }
    } else {
        // Front-end: log and fail gracefully (no output)
        // Error is already logged above
    }
}

/**
 * Handle activation errors gracefully.
 *
 * @since 1.1.0
 * @param string $message Error message.
 * @param string $title Error title.
 * @param bool $back_link Whether to show back link.
 * @return void
 */
function doc_handle_activation_error(string $message, string $title = '', bool $back_link = true): void {
    $title = !empty($title) ? $title : esc_html__('Plugin Activation Error', 'dynamic-online-services');
    
    // Log error
    if (function_exists('doc_log_error')) {
        doc_log_error($message, 'error', array('context' => 'activation'));
    }

    // Create exception
    $exception = new DOC_Exception($message, 'activation_error', 0, array(), 500);
    do_action('doc_activation_error', $exception);

    // Use wp_die for activation errors (required by WordPress)
    wp_die(
        esc_html($message),
        esc_html($title),
        array(
            'response' => 500,
            'back_link' => $back_link,
        )
    );
}

/**
 * Handle permission errors.
 *
 * @since 1.1.0
 * @param string $message Error message.
 * @param string $capability Required capability.
 * @return void
 */
function doc_handle_permission_error(string $message = '', string $capability = 'manage_options'): void {
    if (empty($message)) {
        $message = esc_html__('You do not have sufficient permissions to perform this action.', 'dynamic-online-services');
    }

    // Log error
    if (function_exists('doc_log_error')) {
        doc_log_error(
            $message,
            'error',
            array(
                'context' => 'permission',
                'capability' => $capability,
                'current_user_id' => get_current_user_id(),
            )
        );
    }

    // Create exception
    $exception = new DOC_Exception($message, 'permission_error', 403, array('capability' => $capability), 403);
    
    // Handle exception
    doc_handle_exception($exception, false);
}

