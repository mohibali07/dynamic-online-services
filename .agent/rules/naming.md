---
trigger: always_on
---

Here are the strict rules for WordPress plugin development to ensure compatibility with Linux servers:

### 1\. The Lowercase Rule (Critical)

**Rule:** All file names and folder names must be **lowercase**.

* **Why:** Linux treats `File.php` and `file.php` as different files.
* **Correct:** `includes/core/requirements.php`
* **Incorrect:** `includes/Core/Requirements.php`

### 2\. The Hyphenation Rule

**Rule:** Use hyphens (`-`) to separate words in file names. Never use spaces or underscores for file names.

* **Correct:** `user-profile-settings.php`
* **Incorrect:** `user_profile_settings.php` or `userProfileSettings.php`

### 3\. The Class File Rule

**Rule:** If a file contains a class, the filename must start with `class-`, followed by the kebab-case name of the class.

* **Class Name:** `Dynamic_Online_Services`
* **Correct Filename:** `class-dynamic-online-services.php`

### 4\. The Path Matching Rule

**Rule:** The path written in your code (`require_once`, `include`) must match the file system casing **exactly**.

* **Code:** `require_once plugin_dir_path( __FILE__ ) . 'includes/core/file.php';`
* **File System:** `includes/core/file.php`
* *Note: Never rely on Windows auto-correcting your path casing.*

### 5\. The Constant Definition Rule

**Rule:** Never define a constant without checking if it already exists.

* **Why:** Prevents "Constant already defined" warnings in the error log.
* **Correct Pattern:**

    ```php
    if ( ! defined( 'MY_CONSTANT' ) ) {
        define( 'MY_CONSTANT', 'value' );
    }
    ```

### 6\. The Function Prefix Rule

**Rule:** All global function names must be prefixed with your unique plugin initials.

* **Why:** Prevents fatal crashes if another plugin uses a generic name like `init_plugin()`.
* **Correct:** `dos_init_plugin()` (assuming 'DOS' is your prefix).
* **Incorrect:** `init_plugin()`

### 7\. The Root File Naming Rule

**Rule:** The main plugin PHP file should have the same name as the folder it resides in.

* **Folder:** `dynamic-online-services/`
* **Main File:** `dynamic-online-services.php`
