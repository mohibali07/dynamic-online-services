---
trigger: always_on
---

# WordPress Plugin Development Official Guidelines

> **Source**: [WordPress Plugin Developer Handbook](https://developer.wordpress.org/plugins/)  
> **Last Updated**: December 2025

This document consolidates official WordPress plugin development best practices, security guidelines, and architectural standards from the WordPress Plugin Handbook.

---

## Table of Contents

1. [Core Principles](#core-principles)
2. [Architecture & File Structure](#architecture--file-structure)
3. [Naming Conventions & Namespacing](#naming-conventions--namespacing)
4. [Security Guidelines](#security-guidelines)
5. [Hooks System](#hooks-system)
6. [Plugin Header Requirements](#plugin-header-requirements)
7. [Activation, Deactivation & Uninstall](#activation-deactivation--uninstall)
8. [Database & Performance](#database--performance)
9. [Custom Post Types & Taxonomies](#custom-post-types--taxonomies)
10. [Compatibility Requirements](#compatibility-requirements)

---

## Core Principles

### Security Mindset

**Never trust any data.** This is the golden rule of WordPress development:

1. **Never trust user input** - Always validate and sanitize
2. **Escape as late as possible** - Right before output
3. **Escape everything from untrusted sources** - Users, databases, third-party APIs
4. **Never assume anything** - Verify all data
5. **Validation > Sanitization** - Reject bad data when possible

### Modern PHP Requirements

```php
<?php
declare(strict_types=1);

/**
 * All PHP files MUST start with strict typing
 * Use return type declarations for all functions
 */
function get_user_count(): int {
    return 5;
}
```

**Requirements**:

- Use `declare(strict_types=1);` in every PHP file
- Use return type declarations for all functions and methods
- Avoid PHP 5.6/7.x style code
- Linux servers are case-sensitive - be mindful of file naming

---

## Architecture & File Structure

### Directory Structure

```
plugin-name/
├── plugin-name.php          # Main plugin file (required)
├── uninstall.php            # Uninstall cleanup (optional but recommended)
├── readme.txt               # WordPress.org readme
├── includes/                # Core plugin classes and functions
│   ├── class-main.php
│   └── class-admin.php
├── admin/                   # Admin-specific files
│   ├── css/
│   ├── js/
│   └── views/
├── public/                  # Public-facing files
│   ├── css/
│   ├── js/
│   └── views/
└── languages/               # Translation files
```

### Architecture Patterns

WordPress supports three main architectural patterns:

1. **Single File with Functions** - Simple plugins
2. **Single File with Class** - Medium complexity
3. **Main File + Multiple Classes** - Complex plugins (recommended)

**Modern Recommendation**: Use object-oriented programming with namespaces for all but the simplest plugins.

### File Organization Rules

- Root level contains only `plugin-name.php` and optionally `uninstall.php`
- All other files MUST be organized into subdirectories
- Use feature-based organization, not type-based (e.g., `features/auth/` not `controllers/`)

---

## Naming Conventions & Namespacing

### Avoid Naming Collisions

**Critical**: Global namespace conflicts are the #1 cause of plugin incompatibility.

#### 1. Use Unique Prefixes

**Minimum 4-5 characters, unique to your plugin:**

```php
<?php
// Good Examples
function ecpt_save_post() { /* ... */ }
define( 'ECPT_LICENSE', true );
class ECPT_Admin {}
update_option( 'ecpt_settings', $settings );
```

#### 2. Forbidden Prefixes

**NEVER use these prefixes** (reserved for WordPress core):

- `__` (double underscore)
- `wp_`
- `WordPress`
- `_` (single underscore)

**Exception**: Using core functions like `__()` or `_n()` for translations is fine.

#### 3. Use Namespaces (Recommended)

```php
<?php
declare(strict_types=1);

namespace MyStudio\AwesomePlugin\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Settings {
    public static function init(): void {
        // Your code here
    }
}
```

#### 4. What Must Be Prefixed/Namespaced

- Functions (unless namespaced)
- Classes, interfaces, and traits (unless namespaced)
- Namespaces themselves
- Global variables
- Options and transients
- Database table names

#### 5. Check for Existing Implementations

```php
<?php
// Check before defining
if ( ! class_exists( 'WPOrg_Plugin' ) ) {
    class WPOrg_Plugin {
        // Your code
    }
}

// Available functions:
// - isset() - for variables
// - function_exists() - for functions
// - class_exists() - for classes
// - defined() - for constants
```

**Warning**: Using `if ( ! function_exists() )` around all functions is an anti-pattern. If another plugin loads first with the same name, your plugin breaks.

---

## Security Guidelines

### The Security Trinity: Validate, Sanitize, Escape

#### 1. Sanitizing Input

**Rule**: Sanitize data when you receive it (on input).

```php
<?php
// Sanitize text input
$title = sanitize_text_field( $_POST['title'] );
update_post_meta( $post->ID, 'title', $title );

// Sanitize email
$email = sanitize_email( $_POST['email'] );

// Sanitize URL
$url = sanitize_url( $_POST['website'] );
```

**Common Sanitization Functions**:

- `sanitize_text_field()` - Text input
- `sanitize_textarea_field()` - Textarea input
- `sanitize_email()` - Email addresses
- `sanitize_file_name()` - File names
- `sanitize_hex_color()` - Hex colors
- `sanitize_key()` - Keys/slugs
- `sanitize_title()` - Post titles
- `sanitize_url()` - URLs
- `wp_kses()` - Allow specific HTML tags
- `wp_kses_post()` - Allow post content HTML

#### 2. Escaping Output

**Rule**: Escape as late as possible (right before output).

```php
<?php
// HTML content
echo esc_html( $title );

// HTML attributes
<ul class="<?php echo esc_attr( $stored_class ); ?>">

// URLs
<img src="<?php echo esc_url( $media_url ); ?>" />

// JavaScript
<div onclick='<?php echo esc_js( $value ); ?>' />

// Textarea
<textarea><?php echo esc_textarea( $content ); ?></textarea>

// Allow specific HTML
echo wp_kses_post( $partial_html );
```

**Escaping Functions**:

- `esc_html()` - Remove all HTML
- `esc_attr()` - HTML attributes
- `esc_url()` - URLs (href, src)
- `esc_url_raw()` - URLs for database storage
- `esc_js()` - JavaScript
- `esc_textarea()` - Textarea elements
- `esc_xml()` - XML content
- `wp_kses()` - Custom allowed HTML
- `wp_kses_post()` - Post content HTML
- `wp_kses_data()` - Comment HTML

#### 3. Data Validation

**Rule**: Validation is better than sanitization. Reject invalid data.

```php
<?php
// Validate email
if ( ! is_email( $email ) ) {
    wp_die( 'Invalid email address' );
}

// Validate numeric
if ( ! is_numeric( $user_id ) ) {
    wp_die( 'Invalid user ID' );
}

// Validate against allowed values
$allowed_values = array( 'option1', 'option2', 'option3' );
if ( ! in_array( $value, $allowed_values, true ) ) {
    wp_die( 'Invalid option selected' );
}
```

#### 4. Checking User Capabilities

**Always verify permissions before executing privileged actions:**

```php
<?php
// Check if user can edit posts
if ( ! current_user_can( 'edit_posts' ) ) {
    wp_die( 'You do not have permission to perform this action.' );
}

// Check if user can manage options
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Unauthorized access' );
}
```

**Note**: `is_admin()` only checks if you're on an admin page, NOT if the user is an administrator.

#### 5. Nonces (Security Tokens)

**Use nonces to verify request origin:**

```php
<?php
// Generate nonce
wp_nonce_field( 'my_action', 'my_nonce_field' );

// Verify nonce
if ( ! isset( $_POST['my_nonce_field'] ) || 
     ! wp_verify_nonce( $_POST['my_nonce_field'], 'my_action' ) ) {
    wp_die( 'Security check failed' );
}

// Nonce for URLs
$url = wp_nonce_url( $url, 'action_name' );
```

#### 6. Preventing Direct File Access

**Every PHP file must include:**

```php
<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly
```

This prevents direct access to plugin files when `ABSPATH` is not defined.

---

## Hooks System

### Understanding Hooks

Hooks are the foundation of WordPress extensibility. There are two types:

#### Actions

**Actions** execute code at specific points without returning data.

```php
<?php
// Add an action
add_action( 'init', 'my_plugin_init' );

function my_plugin_init(): void {
    // Runs when WordPress initializes
    register_post_type( 'my_custom_type', $args );
}

// Action with priority and arguments
add_action( 'save_post', 'my_save_function', 10, 2 );

function my_save_function( int $post_id, WP_Post $post ): void {
    // Your code here
}
```

**Common Action Hooks**:

- `init` - WordPress initialization
- `admin_init` - Admin initialization
- `wp_enqueue_scripts` - Enqueue frontend scripts/styles
- `admin_enqueue_scripts` - Enqueue admin scripts/styles
- `save_post` - When a post is saved
- `wp_footer` - Footer of the site
- `admin_menu` - Add admin menu items

#### Filters

**Filters** modify data and return it.

```php
<?php
// Add a filter
add_filter( 'the_content', 'my_content_filter' );

function my_content_filter( string $content ): string {
    // Modify content
    $content .= '<p>Additional content</p>';
    return $content; // MUST return data
}

// Filter with priority and arguments
add_filter( 'the_title', 'my_title_filter', 10, 2 );

function my_title_filter( string $title, int $post_id ): string {
    return strtoupper( $title );
}
```

**Key Differences**:

- **Actions**: Do something, return nothing
- **Filters**: Modify data, MUST return something

#### Creating Custom Hooks

```php
<?php
// Create custom action
do_action( 'my_plugin_custom_action', $arg1, $arg2 );

// Create custom filter
$value = apply_filters( 'my_plugin_custom_filter', $value, $arg1 );
```

### Hook Best Practices

1. **Priority**: Lower numbers run first (default: 10)
2. **Accepted Args**: Specify how many arguments your callback accepts
3. **No Side Effects in Filters**: Don't modify global state in filters
4. **Always Return in Filters**: Even if you don't modify the data

---

## Plugin Header Requirements

### Minimum Header (Required)

```php
<?php
/**
 * Plugin Name: Your Plugin Name
 */
```

### Complete Header (Recommended)

```php
<?php
/**
 * Plugin Name:       Easy Custom Post Types
 * Plugin URI:        https://example.com/plugins/ecpt/
 * Description:       A comprehensive plugin for managing custom post types.
 * Version:           1.0.3
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Your Name
 * Author URI:        https://example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       easy-custom-post-types
 * Domain Path:       /languages
 * Update URI:        https://example.com/plugins/ecpt/
 * Requires Plugins:  woocommerce, advanced-custom-fields
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;
```

### Header Field Definitions

- **Plugin Name** *(required)*: Display name in WordPress admin
- **Plugin URI**: Unique URL to plugin homepage (no WordPress.org URLs)
- **Description**: Brief description (<140 characters)
- **Version**: Current version (e.g., 1.0.3)
- **Requires at least**: Minimum WordPress version
- **Requires PHP**: Minimum PHP version
- **Author**: Plugin author name(s)
- **Author URI**: Author's website
- **License**: License slug (e.g., GPLv2)
- **License URI**: Full license URL
- **Text Domain**: Translation domain (match plugin slug)
- **Domain Path**: Translation files location
- **Network**: Set to `true` for network-only plugins
- **Update URI**: Prevent accidental updates from WordPress.org
- **Requires Plugins**: Comma-separated plugin dependencies

---

## Activation, Deactivation & Uninstall

### Activation Hook

```php
<?php
register_activation_hook( __FILE__, 'my_plugin_activate' );

function my_plugin_activate(): void {
    // Create custom database tables
    global $wpdb;
    $table_name = $
