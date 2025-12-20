---
trigger: always_on
---

WordPress plugin development, ensuring full compatibility with Linux environments and modern mobile-first design standards.

1. File & Directory Architecture
All structural components must prioritize Linux compatibility and modular scalability.
Linux Case-Sensitivity: You must treat all file and folder names as strictly case-sensitive. 1111
Lowercase Rule: All file and directory names must be entirely lowercase. 2222
Kebab-case Naming: Use hyphens (-) to separate words in file and folder names; never use spaces or underscores for these. 3333
Main Loader Naming: The primary plugin PHP file must have the exact same name as the folder it resides in. 4
Directory Structure: The root directory must remain clean, containing only the main loader, uninstall.php, and readme.txt. 5 Organize logic into specific subdirectories:
/includes: Core logic and shared functions. 6
/admin: Dashboard and backend logic. 7
/frontend: Public-facing logic. 8
/cpt: Custom Post Type definitions. 9
/api: REST API endpoints. 10
/assets: Subdivided into /css and /js. 11
Path Handling: Never hardcode slashes; use DIRECTORY_SEPARATOR or plugin_dir_path( __FILE__ ). 12Casing in code must match the file system casing exactly. 13
Direct Access Prevention: Every PHP file must begin with defined( 'ABSPATH' ) || exit; to prevent unauthorized execution. 14

2. PHP Standards & Naming Conventions
Follow modern PHP practices to ensure code stability and avoid collisions with other software.
Strict Typing: Every PHP file must start with declare(strict_types=1);. 15
Namespacing: Implement a unique top-level namespace for the plugin (e.g., namespace MyPlugin\Admin;). 16
Forbidden Prefixes: Never use wp_, _, __, or WordPress for custom functions or classes. 17
Class Casing: Use PascalCase for class names. 18
Class File Prefix: Filenames containing a class must be prefixed with class- followed by the kebab-case name (e.g., class-settings-page.php). 191919191919191919
Function & Variable Casing: Use snake_case for all functions and variables. 20
Global Prefixes: All global functions, constants, and hooks must be prefixed with your unique plugin slug or initials. 21212121
Constant Definitions: Always use UPPER_SNAKE_CASE and wrap definitions in an existence check (if ( ! defined( 'NAME' ) )). 222222222222222222

3. The Security Trinity
Never trust external data. Implement validation, sanitization, and escaping at every stage.
Validation (Input): Reject invalid data immediately using functions like is_email(), is_numeric(), or in_array(). 23
Sanitization (Processing): Clean all data from $_POST or $_GET immediately upon receipt using sanitize_text_field(), sanitize_email(), etc. 24
Escaping (Output): Escape data as late as possible (right before echo) using esc_html() for text, esc_attr() for attributes, or esc_url() for links. 25
CSRF Protection: Secure all forms and actions using nonces (wp_create_nonce and wp_verify_nonce). 26
Permissions: Always verify user roles with current_user_can() before performing privileged actions. 27

4. Database & Hook Management
Prepared Queries: Always use $wpdb->prepare() for custom queries to prevent SQL injection. 28
Table Naming: Custom tables must be lowercase with underscores and use the $wpdb->prefix variable. 29
Hook Implementation:
Use add_action() for execution points. 30
Use add_filter() to modify data (must return the data). 31
Prefix custom hooks (e.g., do_action( 'slug_after_save' )). 32
Asset Handles: Prefix all enqueued script and style handles with your plugin slug. 33

5. Mobile-First Technical Design
Design for components that adapt to their environment rather than just the device screen.
Container Queries: Use @container CSS rules so elements adapt to the size of their specific parent container rather than the browser width. 34343434
Touch Targets: Ensure every clickable element has a minimum area of 44x44 pixels. 35
No Hover Dependencies: Do not hide critical functionality behind hover states; use visible buttons or tap-to-open menus. 36363636
Responsive Tables: Avoid shrinking tables; use CSS to transform rows into "card" views for mobile displays. 37
Native Inputs: Use specific HTML5 types like type="email", type="tel", and type="date" to trigger appropriate mobile keyboards. 38

6. Internationalization (i18n)
Text Domain: The text domain must exactly match the plugin folder's kebab-case slug. 39
Translation Functions: Use __() for returned strings and _e() for echoed strings, always including the unique text domain. 40
