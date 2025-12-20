# Dynamic Online Services

A modern WordPress plugin for managing educational courses with custom post types, taxonomies, and beautiful frontend presentation.

[![WordPress](https://img.shields.io/badge/WordPress-6.9%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v2%2B-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

## Features

✨ **Custom Post Type**: Manage courses with a dedicated post type  
📁 **Hierarchical Taxonomy**: Organize courses into categories  
❓ **FAQ Management**: Built-in FAQ system for each course  
🎨 **Hero Sections**: Customizable hero banners for categories and courses  
🎴 **Service Cards**: Beautiful grid layouts with Gutenberg block support  
⚙️ **Settings Page**: Customize colors, fonts, and styles  
🔌 **REST API**: Full API support for headless implementations  
🎯 **Modern Architecture**: PSR-4 autoloading, strict typing, namespaces  
📱 **Mobile-First**: Container queries for true responsive design  
🔒 **Security**: Follows WordPress security best practices  
⚡ **Performance**: Optimized queries, caching, conditional asset loading

## Requirements

- **WordPress**: 6.9 or higher
- **PHP**: 8.3 or higher
- **Modern Browser**: For container query support (Chrome 105+, Safari 16+, Firefox 110+)

## Installation

### From WordPress Admin

1. Download the latest release
2. Go to **Plugins → Add New → Upload Plugin**
3. Choose the downloaded ZIP file
4. Click **Install Now** and then **Activate**

### Manual Installation

1. Upload the `dynamic-online-services` directory to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress

### Development Installation

```bash
# Clone the repository
git clone https://github.com/techmire-solutions/dynamic-online-services.git
cd dynamic-online-services

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Build assets
npm run build

# For development with hot reload
npm start
```

## Quick Start

### 1. Create Your First Course

1. Go to **Courses → Add New**
2. Enter course title and content
3. Assign to a category
4. Add FAQs (optional)
5. Set featured image
6. Publish

### 2. Display Courses

**Using Gutenberg Block:**

```
Add the "Service Cards" block from the block inserter
Configure options in the sidebar
```

**Using Shortcode:**

```
[service_cards category="web-development" columns="3" limit="6"]
```

### 3. Customize Appearance

Go to **Settings → Dynamic Services** to customize:

- Hero section colors and fonts
- Card colors and styles
- FAQ accordion appearance
- Post type slugs

## Available Shortcodes

### Service Cards

Display courses in a grid layout:

```php
[service_cards 
    category="web-development" 
    columns="3" 
    limit="6" 
    orderby="date" 
    order="DESC"
]
```

**Attributes:**

- `category` - Category slug or ID (comma-separated)
- `ids` - Specific post IDs (comma-separated)
- `columns` - Number of columns (1-6, default: 3)
- `limit` - Number of posts (-1 for all, default: -1)
- `orderby` - Sort by: date, title, menu_order, rand
- `order` - ASC or DESC
- `show_pagination` - true/false

### Category Hero

Display a hero section for a category:

```php
[service_category_hero]
```

Use on category archive pages. Automatically detects the current category.

### Single Service Hero

Display a hero section for a course:

```php
[single_service_hero]
```

Use on single course pages. Automatically uses the current post.

### FAQs Accordion

Display FAQs in an accordion:

```php
[service_faqs_accordion]
```

Use on single course pages. Shows FAQs added to the course.

### Category Content

Display category content with subcategories and courses:

```php
[service_category_content]
```

## Gutenberg Block

The **Service Cards** block provides a visual interface for displaying courses:

1. Add the block from the inserter
2. Configure in the sidebar:
   - Category filter
   - Post IDs
   - Number of columns
   - Limit
   - Order by / Order
   - Pagination toggle
3. Preview updates in real-time

## Developer Documentation

### Hooks & Filters

See [HOOKS.md](HOOKS.md) for a complete list of available hooks and filters.

**Example: Customize card colors**

```php
add_filter('dynos_card_bg_color', function($color) {
    return '#f8f9fa';
});

add_filter('dynos_card_title_color', function($color) {
    return '#212529';
});
```

**Example: Modify query arguments**

```php
add_filter('dynos_service_cards_query_args', function($args, $atts) {
    // Only show courses with featured images
    $args['meta_query'] = array(
        array(
            'key' => '_thumbnail_id',
            'compare' => 'EXISTS'
        )
    );
    return $args;
}, 10, 2);
```

### Constants

Available constants for customization:

```php
DYNOS_VERSION              // Plugin version
DYNOS_PLUGIN_DIR          // Plugin directory path
DYNOS_PLUGIN_URL          // Plugin URL
DYNOS_BREAKPOINT_TABLET   // Tablet breakpoint (default: 768px)
DYNOS_BREAKPOINT_MOBILE   // Mobile breakpoint (default: 480px)
DYNOS_MAX_GRID_COLUMNS    // Maximum columns (default: 6)
DYNOS_MIN_GRID_COLUMNS    // Minimum columns (default: 1)
DYNOS_MAX_POSTS_PER_PAGE  // Query limit (default: 1000)
```

### Programmatic Usage

**Get plugin options:**

```php
use TechmireSolutions\DynamicOnlineServices\Helpers\Options;

$options = Options::get();
$hero_color = Options::get('hero_title_color', '#ffffff');
```

**Query courses:**

```php
use TechmireSolutions\DynamicOnlineServices\Services\CardsQueryService;

$atts = array(
    'category' => 'web-development',
    'limit' => 10,
    'orderby' => 'date'
);

$query = CardsQueryService::get_query($atts);

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        // Your code here
    }
    wp_reset_postdata();
}
```

## Testing

### Run PHPUnit Tests

```bash
# Run all tests
composer test

# Run with coverage
composer test:coverage

# Run specific test
vendor/bin/phpunit tests/unit/AutoloaderTest.php
```

### Run E2E Tests

```bash
# Run Playwright tests
npm run test:e2e

# Run in UI mode
npx playwright test --ui
```

### Linting

```bash
# PHP linting
composer lint
composer lint:fix

# JavaScript linting
npm run lint:js

# CSS linting
npm run lint:css
```

## Performance

The plugin is optimized for performance:

- **Conditional Asset Loading**: CSS/JS only loaded when needed
- **Query Optimization**: `no_found_rows`, meta/term cache preloading
- **Transient Caching**: Breakpoint filters cached for 24 hours
- **Query Limits**: Maximum 1000 posts per query to prevent memory issues
- **Container Queries**: No JavaScript needed for responsive layouts

## Browser Support

- **Modern Browsers**: Full support (Chrome 105+, Safari 16+, Firefox 110+)
- **Older Browsers**: Graceful degradation (grid auto-fit fallback)

## Security

- ✅ Input sanitization on all user inputs
- ✅ Output escaping on all outputs
- ✅ Nonce verification for admin actions
- ✅ Capability checks throughout
- ✅ MIME type validation for file uploads
- ✅ SQL injection prevention via whitelisting

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for development guidelines.

## Support

- **Documentation**: [Plugin Documentation](http://aabtaab.com/)
- **Issues**: [GitHub Issues](https://github.com/techmire-solutions/dynamic-online-services/issues)
- **Author**: [Techmire Solutions](http://techmiresolutions.com/)

## License

This plugin is licensed under the GPL v2 or later.

```
Dynamic Online Services
Copyright (C) 2024 Techmire Solutions

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## Changelog

### 1.1.2

- Performance improvements
- Enhanced security measures
- Bug fixes

### 1.1.0

- Initial release
- Custom post type for Courses
- Hierarchical taxonomy for Course Categories
- FAQ management system
- Hero section shortcodes
- Service cards with Gutenberg block
- Settings page for customization
- REST API support
- Modern PHP architecture (8.3+, strict typing, namespaces)
- Container queries for responsive design

---

**Made with ❤️ by [Techmire Solutions](http://techmiresolutions.com/)**
