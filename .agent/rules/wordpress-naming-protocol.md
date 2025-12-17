---
trigger: always_on
---

Developing a WordPress plugin that works seamlessly across different operating systems (Linux, Windows, macOS) requires strict adherence to naming protocols.

The single biggest issue developers face when moving from a local environment (often Windows or macOS) to a production server (usually Linux) is **Case Sensitivity**. Windows is case-insensitive; Linux is case-sensitive.

Here is the comprehensive guide to naming best practices for cross-platform WordPress plugin development.

-----

### 1\. Files and Directories (Crucial for Linux)

This is where 90% of cross-platform errors occur.

* **Rule:** Always use **lowercase** letters and **hyphens** (kebab-case).
* **Why:** On Windows, `require 'MyFile.php'` will successfully load `myfile.php`. On Linux, this will cause a **Fatal Error: Failed to open stream**.
* **Standard:**
  * **Good:** `/includes/class-user-profile.php`
  * **Bad:** `/Includes/ClassUserProfile.php` or `/includes/class_user_profile.php`

> **Note:** WordPress Coding Standards recommend prefixing class files with `class-`.
>
> * Example: `class-my-plugin-loader.php`

### 2\. PHP Classes and Namespaces

Modern WordPress development should use PHP Namespaces to avoid clashes with other plugins or the WordPress core.

* **Class Names:** Use **PascalCase** (UpperCamelCase).
* **Namespaces:** Use a unique top-level namespace based on your plugin name.
* **Prefixing:** If *not* using namespaces, you must prefix every class with your specific identifier (e.g., `MyPlugin_Class_Name`).

**Modern Approach (Recommended):**

```php
namespace MyPlugin\Admin;

class SettingsPage {
    // Code here
}
```

**Legacy/Standard Approach:**

```php
class My_Plugin_Settings_Page {
    // Code here
}
```

### 3\. Functions and Variables

WordPress relies heavily on procedural code. If you are writing global functions (outside of a class), uniqueness is mandatory.

* **Style:** Use **snake\_case** (lowercase with underscores).
* **Prefixing:** **MANDATORY** for global functions.
* **Variables:** Descriptive snake\_case.

**Example:**

```php
// Good
function my_plugin_save_settings( $user_id ) { ... }
$user_meta = ...;

// Bad (High risk of conflict)
function save_settings( $id ) { ... }
$data = ...;
```

### 4\. Constants

Constants generally hold configuration data or paths.

* **Style:** Use **UPPER\_SNAKE\_CASE**.
* **Prefixing:** Mandatory.

**Example:**

```php
define( 'MY_PLUGIN_VERSION', '1.0.0' );
define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
```

### 5\. Database Tables

If your plugin creates custom tables, they must follow MySQL conventions which can also be case-sensitive depending on the server configuration.

* **Style:** **lowercase** with underscores.
* **Prefix:** Always respect the global WordPress database prefix (`$wpdb->prefix`), followed by your plugin identifier.

**Example:**

```php
global $wpdb;
$table_name = $wpdb->prefix . 'myplugin_customers'; // Result: wp_myplugin_customers
```

### 6\. Hook Handles (Actions and Filters)

When you define custom hooks for others to extend your plugin, naming is key for discoverability.

* **Style:** **snake\_case**.
* **Prefix:** Start with your plugin slug.

**Example:**

```php
do_action( 'my_plugin_after_payment_processed', $order_id );
```

### 7\. Asset Files (JS/CSS) & Enqueuing

When registering scripts and styles, the "handle" name must be unique.

* **File Names:** Kebab-case (e.g., `admin-script.js`, `frontend-style.css`).
* **Handles:** Prefix with your plugin slug.

**Example:**

```php
wp_enqueue_script( 'my-plugin-admin-js', ... ); // Good
wp_enqueue_script( 'admin-js', ... ); // Bad - will conflict with other plugins
```

### 8\. Text Domain (Internationalization)

For your plugin to be translatable, the text domain must match your plugin's directory slug exactly.

* **Rule:** Kebab-case, matching the folder name.

**Example:**

```php
// If folder is /my-awesome-plugin/
load_plugin_textdomain( 'my-awesome-plugin', false, ... );
_e( 'Hello World', 'my-awesome-plugin' );
```

-----

### Summary Checklist

| Component | Naming Convention | Example |
| :--- | :--- | :--- |
| **Files** | Lowercase + Hyphens | `admin-settings.php` |
| **Classes** | PascalCase | `UserProfile` |
| **Functions** | snake\_case + Prefix | `my_plugin_init` |
| **Variables** | snake\_case | `$user_data` |
| **Constants** | UPPER\_CASE + Prefix | `MY_PLUGIN_DEBUG` |
| **DB Tables** | Lowercase + WP Prefix | `wp_myplugin_logs` |
| **Directory Paths** | **Do not hardcode slashes** | Use `plugin_dir_path()` |

### A Note on Directory Separators

Since you mentioned "Linux and all other servers," never hardcode path separators like `\` (Windows) or `/` (Linux/Mac) when building file paths in PHP.

**Do this:**

```php
require_once plugin_dir_path( __FILE__ ) . 'includes/class-loader.php';
```

**Or use the PHP constant:**

```php
$path = $dir . DIRECTORY_SEPARATOR . 'file.php';
```
