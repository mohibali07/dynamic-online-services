# Changelog

All notable changes to the Dynamic Online Services plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.2] - 2025-12-17

### Fixed

- **Critical:** Fixed broken error logging in autoloader - sprintf was missing format string ([Issue #1](https://github.com/techmire-solutions/dynamic-online-services/issues/1))
- **Security:** Moved ABSPATH check to top of helpers.php to prevent direct file access
- **Version Consistency:** Standardized version number across all files (plugin header, constants, package files)

### Changed

- Removed legacy code comments and outdated references
- Improved code documentation and inline comments

### Added

- Comprehensive README.md with development setup and contribution guidelines
- This CHANGELOG.md following Keep a Changelog format
- Additional documentation for filters and hooks

## [1.1.1] - 2024-XX-XX

### Added

- FAQ management system with admin meta box
- Maximum FAQ limit (100 per post) for performance
- Validation for FAQ arrays to prevent memory issues

### Fixed

- SQL injection prevention in orderby parameter
- Improved sanitization for CSS values

## [1.1.0] - 2024-XX-XX

### Added

- Custom Post Type for Services with REST API support
- Hierarchical taxonomy for Service Categories
- Hero section shortcodes for categories and services
- Service cards display shortcode with customizable grid
- FAQ accordion shortcode for individual services
- Gutenberg block for service cards
- Comprehensive settings page with color and font customization
- Conditional asset loading for better performance
- Transient caching for filter values (24-hour cache)
- LiteSpeed Cache integration
- ACF (Advanced Custom Fields) helper functions
- Internationalization support with text domain loading

### Performance

- Default posts per page reduced to 100 (from 1000)
- Conditional script and style enqueuing
- Optimized query parameters with sensible defaults

### Security

- Nonce verification for all form submissions
- Capability checks for all privileged operations
- Comprehensive input sanitization and output escaping
- CSS injection prevention with whitelist-based sanitization
- SQL injection prevention with orderby whitelisting

### Developer Features

- PSR-4 autoloading with custom kebab-case to PascalCase converter
- Strict typing throughout (`declare(strict_types=1)`)
- Return type declarations for all methods
- Extensive hook documentation (14KB documentation file)
- Multiple extensibility filters:
  - `dynos_breakpoint_tablet` - Customize tablet breakpoint
  - `dynos_breakpoint_mobile` - Customize mobile breakpoint
  - `dynos_max_grid_columns` - Maximum grid columns
  - `dynos_min_grid_columns` - Minimum grid columns
  - `dynos_max_posts_per_page` - Query limit
  - `dynos_max_taxonomy_depth` - Prevent infinite loops
  - `dynos_default_excerpt_length` - Excerpt word count
  - `dynos_default_grid_min_width` - Card minimum width
  - `dynos_max_faqs_per_post` - FAQ limit per service

### Code Quality

- PHPUnit test suite with WP_Mock
- Playwright E2E tests
- PHPCS configuration with WordPress Coding Standards
- PHP-CS-Fixer for automatic code formatting
- Proper error handling with try-catch blocks
- Admin notices for activation/initialization errors

### Architecture

- Singleton pattern for main plugin class
- Interface-based design (Registrable, RendererInterface)
- Service classes for complex operations
- Separate renderer classes for different display components
- Modular file organization by feature

## [1.0.0] - 2024-XX-XX

### Added

- Initial plugin release
- Basic custom post type functionality

---

## Upgrade Notice

### 1.1.2

Critical security and bug fixes. Update immediately. No database changes required.

### 1.1.0

Major feature release. Adds Gutenberg blocks, FAQ management, and comprehensive settings. Backup your site before updating.

---

## Links

- [GitHub Releases](https://github.com/techmire-solutions/dynamic-online-services/releases)
- [Issue Tracker](https://github.com/techmire-solutions/dynamic-online-services/issues)
- [Documentation](https://github.com/techmire-solutions/dynamic-online-services/wiki)

---

**Legend:**

- `Added` for new features
- `Changed` for changes in existing functionality
- `Deprecated` for soon-to-be removed features
- `Removed` for now removed features
- `Fixed` for any bug fixes
- `Security` for vulnerability fixes
