# Plugin Purpose: Dynamic Online Services

## Overview

**Dynamic Online Services** is a WordPress plugin developed by Techmire Solutions designed to manage educational content, specifically "Courses" and their associated categories. It provides a structured way to handle course data, present it effectively on the frontend using custom designs (like Hero sections and Cards), and allows for customization via a dedicated settings page.

## Key Goals

1. **Structured Content Management**: Separates course content from standard posts/pages using Custom Post Types.
2. **Hierarchical Organization**: Uses custom taxonomies to organize courses into categories.
3. **Frontend Presentation**: Offers ready-to-use UI components (Course Cards, Accordions, Hero Sections) via Shortcodes.
4. **Customization**: Empowers administrators to styling the output without touching code.

## Core Features

* **Custom Post Type**: `Courses` - The main content entity.
* **Custom Taxonomy**: `Course Categories` - For organizing courses (hierarchical).
* **FAQ Management**: Built-in support for adding FAQs to individual courses.
* **Hero Sections**: Customizable hero banners for both categories and individual course pages.
* **Shortcodes**: A suite of shortcodes to display content anywhere on the site.
  * `[service_faqs_accordion]`: Displays Course FAQs in an accordion format.
  * `[service_category_content]`: Displays content for a specific category.
  * `[service_category_hero]`: Displays the hero section for a category.
  * `[single_service_hero]`: Displays the hero section for a single course.
  * `[service_cards]` (or `[course_cards]`): Displays courses in a customizable grid layout anywhere on the site.
* **Settings Page**: A backend interface to customize colors, fonts, and styles.
* **REST API**: Full support for headless implementations or external integrations.

## Technical Summary

* **Version**: 1.1.0
* **Requires**: PHP 8.3+, WordPress 6.9+
* **Architecture**: Follows modern WordPress plugin standards with PSR-4 autoloading, strict typing, and class-based structure (`includes/` directory).
* **Performance**: Optimized asset loading (CSS/JS) to prevent bloat.
* **Security**: Adheres to WordPress security best practices (escaping, sanitization, nonces).
