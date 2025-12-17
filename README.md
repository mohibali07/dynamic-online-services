# Dynamic Online Services

A modern WordPress plugin for managing dynamic online services and their categories with advanced filtering, customizable styles, and a comprehensive admin interface.

**Version:** 1.1.2  
**Requires:** WordPress 6.9+, PHP 8.3+  
**License:** GPL-2.0-or-later

---

## Features

- ✅ **Custom Post Type:** Fully-featured Services post type with REST API support
- ✅ **Hierarchical Taxonomy:** Category system for organizing services
- ✅ **FAQ Management:** Add and manage FAQs for individual services
- ✅ **Hero Sections:** Customizable hero sections for categories and services
- ✅ **Flexible Display:** Multiple shortcodes for content display
- ✅ **Gutenberg Blocks:** Service cards block for the block editor
- ✅ **Customization:** Extensive styling options via settings page
- ✅ **Performance Optimized:** Conditional asset loading and caching
- ✅ **Developer Friendly:** Extensive hooks, filters, and extensibility
- ✅ **Modern PHP:** Strict typing, namespaces, PSR-4 autoloading

---

## Installation

### From WordPress Admin

1. Upload the `dynamic-online-services` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Settings → Dynamic Services to configure

### Composer Installation

```bash
composer require techmire-solutions/dynamic-online-services
```

---

## Development Setup

### Prerequisites

- PHP 8.3 or higher
- Node.js 14.0 or higher
- Composer 2.0 or higher
- npm 6.0 or higher

### Initial Setup

```bash
# Clone the repository
git clone https://github.com/techmire-solutions/dynamic-online-services.git
cd dynamic-online-services

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Build assets for development
npm run start

# For production build
npm run build
```

---

## Available Shortcodes

### Service Cards

Display a grid of service cards:

```
[dynos_service_cards category="web-development" limit="6"]
```

### Single Service Hero

Display hero section for a service:

```
[single_service_hero]
```

### Category Hero

Display hero section for a category:

```
[service_category_hero]
```

### Category Content

Display category description and services:

```
[service_category_content]
```

### FAQ Accordion

Display FAQs for current service:

```
[service_faqs_accordion]
```

---

## Testing

### PHP Unit Tests

```bash
#Run all tests
composer test

# Run with coverage report
composer test:coverage

# View coverage in browser
open tests/coverage/index.html
```

### End-to-End Tests

```bash
# Run Playwright E2E tests
npm run test:e2e
```

### Code Quality

```bash
# PHP linting
composer lint

# Auto-fix PHP code style
composer lint:fix

# JavaScript linting
npm run lint
```

---

## Filters & Actions

The plugin provides numerous hooks for customization. See [`includes/hooks-documentation.php`](includes/hooks-documentation.php) for complete documentation.

### Common Filters

```php
// Customize tablet breakpoint
add_filter('dynos_breakpoint_tablet', function() {
    return '991px';
});

// Change maximum posts per page
add_filter('dynos_max_posts_per_page', function() {
    return 200;
});

// Customize grid columns
add_filter('dynos_max_grid_columns', function() {
    return 4;
});
```

---

## File Structure

```
dynamic-online-services/
├── includes/              # Core plugin logic (PSR-4 autoloaded)
│   ├── admin/            # Admin-specific functionality
│   ├── core/             # Core classes (Plugin, Activator, etc.)
│   ├── faqs/             # FAQ management
│   ├── helpers/          # Helper utilities
│   ├── post-types/       # Custom post type definitions
│   ├── shortcodes/       # Shortcode handlers
│   ├── taxonomies/       # Custom taxonomy definitions
│   └── ...
├── src/                  # Frontend asset sources
│   ├── admin/            # Admin JavaScript/CSS
│   └── blocks/           # Gutenberg blocks
├── build/                # Compiled assets (auto-generated)
├── tests/                # Unit and E2E tests
│   ├── unit/            # PHPUnit tests
│   └── e2e/             # Playwright tests
└── languages/            # Translation files
```

---

## Performance Notes

### Default Limits

The plugin has sensible performancedefaults:

- **Posts per page:** 100 (changed from 1000 in v1.2.0)
- **Max FAQs per post:** 100
- **Breakpoint caching:** 24 hours

### Optimization Tips

1. Use pagination for large catalogs (100+ services)
2. Organize services into categories for better navigation
3. Consider using filters/search instead of displaying all items
4. Clear transients after major configuration changes:

```php
delete_transient('dynos_breakpoint_tablet_cached');
delete_transient('dynos_breakpoint_mobile_cached');
```

---

## Compatibility

### Third-Party Integrations

- **LiteSpeed Cache:** Built-in integration
- **Advanced Custom Fields (ACF):** Helper functions provided
- **WordPress REST API:** Full support

### Minimum Requirements

- **WordPress:** 6.9+
- **PHP:** 8.3+
- **Browser Support:** Modern browsers (see `.browserslistrc`)

---

## Contributing

We welcome contributions! Please follow these guidelines:

1. **Fork** the repository
2. **Create a feature branch** (`git checkout -b feature/amazing-feature`)
3. **Run tests** to ensure nothing breaks
4. **Follow coding standards** (PHPCS + WordPress Coding Standards)
5. **Commit your changes** with clear, descriptive messages
6. **Push to your branch**
7. **Open a Pull Request**

### Coding Standards

```bash
# Check code standards
composer lint

# Auto-fix issues
composer lint:fix
```

---

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history.

---

## License

This plugin is licensed under the GPL-2.0-or-later license. See [LICENSE](LICENSE) for details.

---

## Support

- **Issues:** [GitHub Issues](https://github.com/techmire-solutions/dynamic-online-services/issues)
- **Documentation:** [Wiki](https://github.com/techmire-solutions/dynamic-online-services/wiki)
- **Website:** [Techmire Solutions](http://techmiresolutions.com/)

---

## Credits

**Developed by:** [Techmire Solutions](http://techmiresolutions.com/)  
**Author:** Techmire Solutions Team

---

*Built with ❤️ following WordPress best practices and modern PHP standards.*
