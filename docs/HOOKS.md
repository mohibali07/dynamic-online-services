# Hooks & Filters Reference

This document provides a comprehensive reference of all available hooks and filters in the Dynamic Online Services plugin.

## Table of Contents

- [Filters](#filters)
  - [Options & Settings](#options--settings)
  - [Post Types & Taxonomies](#post-types--taxonomies)
  - [Permalinks](#permalinks)
  - [Hero Sections](#hero-sections)
  - [Service Cards](#service-cards)
  - [FAQs](#faqs)
  - [Category Content](#category-content)
  - [Images & Fonts](#images--fonts)
- [Actions](#actions)
- [Constants](#constants)

---

## Filters

### Options & Settings

#### `dynos_get_options`

Filter all plugin options before they are returned.

```php
add_filter('dynos_get_options', function($options) {
    // Modify all options
    return $options;
});
```

**Parameters:**

- `$options` (array) - The complete options array

**Returns:** (array) Modified options array

---

#### `dynos_get_option`

Filter individual option values.

```php
add_filter('dynos_get_option', function($value, $key, $default, $options) {
    if ($key === 'hero_title_color') {
        return '#custom-color';
    }
    return $value;
}, 10, 4);
```

**Parameters:**

- `$value` (mixed) - The option value
- `$key` (string) - The option key
- `$default` (mixed) - The default value
- `$options` (array) - The complete options array

**Returns:** (mixed) Modified option value

---

### Post Types & Taxonomies

#### `dynos_service_cpt_labels`

Customize post type labels.

```php
add_filter('dynos_service_cpt_labels', function($labels) {
    $labels['name'] = 'Online Courses';
    $labels['singular_name'] = 'Online Course';
    return $labels;
});
```

**Parameters:**

- `$labels` (array) - Array of labels for the service post type

**Returns:** (array) Modified labels array

---

#### `dynos_service_cpt_args`

Customize post type registration arguments.

```php
add_filter('dynos_service_cpt_args', function($args) {
    $args['menu_icon'] = 'dashicons-welcome-learn-more';
    $args['supports'][] = 'author';
    return $args;
});
```

**Parameters:**

- `$args` (array) - Array of arguments for the service post type

**Returns:** (array) Modified arguments array

---

#### `dynos_services_category_tax_labels`

Customize taxonomy labels.

```php
add_filter('dynos_services_category_tax_labels', function($labels) {
    $labels['name'] = 'Course Topics';
    return $labels;
});
```

**Parameters:**

- `$labels` (array) - Array of labels for the taxonomy

**Returns:** (array) Modified labels array

---

#### `dynos_services_category_tax_args`

Customize taxonomy registration arguments.

```php
add_filter('dynos_services_category_tax_args', function($args) {
    $args['show_in_rest'] = true;
    $args['show_admin_column'] = true;
    return $args;
});
```

**Parameters:**

- `$args` (array) - Array of arguments for the taxonomy

**Returns:** (array) Modified arguments array

---

### Permalinks

#### `dynos_service_permalink_before_process`

Filter service permalink before category replacement.

```php
add_filter('dynos_service_permalink_before_process', function($post_link, $post) {
    // Modify permalink before processing
    return $post_link;
}, 10, 2);
```

**Parameters:**

- `$post_link` (string) - The post permalink
- `$post` (WP_Post) - The post object

**Returns:** (string) Modified permalink

---

#### `dynos_service_permalink_after_process`

Filter service permalink after category replacement.

```php
add_filter('dynos_service_permalink_after_process', function($post_link, $post, $term_path) {
    // Modify final permalink
    return $post_link;
}, 10, 3);
```

**Parameters:**

- `$post_link` (string) - The post permalink
- `$post` (WP_Post) - The post object
- `$term_path` (string) - The category path used in the permalink

**Returns:** (string) Modified permalink

---

### Hero Sections

#### `dynos_hero_overlay_color`

Customize hero overlay color.

```php
add_filter('dynos_hero_overlay_color', function($overlay_color) {
    return 'rgba(0, 0, 0, 0.6)';
});
```

**Parameters:**

- `$overlay_color` (string) - The overlay color

**Returns:** (string) Modified overlay color

---

#### `dynos_hero_dynamic_css`

Filter hero section dynamic CSS.

```php
add_filter('dynos_hero_dynamic_css', function($css, $overlay_color, $options) {
    $css .= '.hero-custom { color: red; }';
    return $css;
}, 10, 3);
```

**Parameters:**

- `$css` (string) - The CSS string
- `$overlay_color` (string) - The overlay color
- `$options` (array) - The options array

**Returns:** (string) Modified CSS string

---

#### `dynos_category_hero_title`

Customize category hero title.

```php
add_filter('dynos_category_hero_title', function($title, $term) {
    return strtoupper($title);
}, 10, 2);
```

**Parameters:**

- `$title` (string) - The title
- `$term` (WP_Term) - The term object

**Returns:** (string) Modified title

---

#### `dynos_service_hero_description`

Customize service hero description.

```php
add_filter('dynos_service_hero_description', function($description, $post) {
    return wp_trim_words($description, 30);
}, 10, 2);
```

**Parameters:**

- `$description` (string) - The description
- `$post` (WP_Post) - The post object

**Returns:** (string) Modified description

---

### Service Cards

#### `dynos_service_cards_query_args`

Modify query arguments for service cards.

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

**Parameters:**

- `$args` (array) - WP_Query arguments
- `$atts` (array) - Shortcode attributes

**Returns:** (array) Modified query arguments

---

#### `dynos_card_bg_color`

Customize card background color.

```php
add_filter('dynos_card_bg_color', function($color) {
    return '#f8f9fa';
});
```

**Parameters:**

- `$card_bg` (string) - The background color

**Returns:** (string) Modified background color

---

#### `dynos_card_title_hover_color`

Customize card title hover color.

```php
add_filter('dynos_card_title_hover_color', function($color, $options) {
    return '#ff6b6b';
}, 10, 2);
```

**Parameters:**

- `$card_title_hover_color` (string) - The hover color
- `$options` (array) - The options array

**Returns:** (string) Modified hover color

---

#### `dynos_card_dynamic_css`

Filter card dynamic CSS.

```php
add_filter('dynos_card_dynamic_css', function($css, $options) {
    $css .= '.service-card:hover { box-shadow: 0 8px 16px rgba(0,0,0,0.2); }';
    return $css;
}, 10, 2);
```

**Parameters:**

- `$css` (string) - The CSS string
- `$options` (array) - The options array

**Returns:** (string) Modified CSS string

---

### FAQs

#### `dynos_before_save_faqs`

Filter FAQs before saving to database.

```php
add_filter('dynos_before_save_faqs', function($faqs, $post_id) {
    // Limit to 10 FAQs
    return array_slice($faqs, 0, 10);
}, 10, 2);
```

**Parameters:**

- `$faqs` (array) - Array of FAQ items
- `$post_id` (int) - The post ID

**Returns:** (array) Modified FAQs array

---

#### `dynos_faqs_before_display`

Filter FAQs before display.

```php
add_filter('dynos_faqs_before_display', function($faqs, $post_id) {
    // Sort FAQs alphabetically
    usort($faqs, function($a, $b) {
        return strcmp($a['question'], $b['question']);
    });
    return $faqs;
}, 10, 2);
```

**Parameters:**

- `$faqs` (array) - Array of FAQ items
- `$post_id` (int) - The post ID

**Returns:** (array) Modified FAQs array

---

#### `dynos_faq_item`

Filter individual FAQ item before display.

```php
add_filter('dynos_faq_item', function($faq_item, $index, $post_id) {
    // Add prefix to questions
    $faq_item['question'] = 'Q: ' . $faq_item['question'];
    return $faq_item;
}, 10, 3);
```

**Parameters:**

- `$faq_item` (array) - Array with 'question' and 'answer' keys
- `$index` (int) - The FAQ index
- `$post_id` (int) - The post ID

**Returns:** (array) Modified FAQ item array

---

#### `dynos_faq_dynamic_css`

Filter FAQ accordion dynamic CSS.

```php
add_filter('dynos_faq_dynamic_css', function($css, $options) {
    $css .= '.faq-item { margin-bottom: 20px; }';
    return $css;
}, 10, 2);
```

**Parameters:**

- `$css` (string) - The CSS string
- `$options` (array) - The options array

**Returns:** (string) Modified CSS string

---

### Category Content

#### `dynos_category_content_services`

Filter services array for category content.

```php
add_filter('dynos_category_content_services', function($services, $term) {
    // Only show published services
    return array_filter($services, function($service) {
        return $service->post_status === 'publish';
    });
}, 10, 2);
```

**Parameters:**

- `$services` (array) - Array of WP_Post objects
- `$term` (WP_Term) - The term object

**Returns:** (array) Modified services array

---

#### `dynos_category_content_button_text`

Customize card button text.

```php
add_filter('dynos_category_content_button_text', function($button_text, $item) {
    if ($item['type'] === 'category') {
        return 'Explore Category';
    }
    return 'View Course';
}, 10, 2);
```

**Parameters:**

- `$button_text` (string) - The button text
- `$item` (array) - The item array

**Returns:** (string) Modified button text

---

### Images & Fonts

#### `dynos_placeholder_image_url`

Customize placeholder image URL.

```php
add_filter('dynos_placeholder_image_url', function($url, $size) {
    return 'https://via.placeholder.com/400x300';
}, 10, 2);
```

**Parameters:**

- `$url` (string) - The placeholder URL (empty by default)
- `$size` (string) - The image size requested

**Returns:** (string) Modified placeholder URL

---

#### `dynos_google_font_family`

Filter Google Font family name.

```php
add_filter('dynos_google_font_family', function($font_family, $handle) {
    return 'Inter';
}, 10, 2);
```

**Parameters:**

- `$font_family` (string) - The font family name
- `$handle` (string) - The style handle

**Returns:** (string) Modified font family name

---

## Actions

### `dynos_after_save_faqs`

Fired after FAQs are saved to the database.

```php
add_action('dynos_after_save_faqs', function($post_id, $new_faqs) {
    // Clear related caches
    wp_cache_delete('faqs_' . $post_id, 'dynos');
}, 10, 2);
```

**Parameters:**

- `$post_id` (int) - The post ID
- `$new_faqs` (array) - The saved FAQs array

---

### `dynos_after_save_category_thumbnail`

Fired after category thumbnail is saved.

```php
add_action('dynos_after_save_category_thumbnail', function($term_id, $thumbnail_id) {
    // Generate additional image sizes
    wp_update_attachment_metadata($thumbnail_id, wp_generate_attachment_metadata($thumbnail_id, get_attached_file($thumbnail_id)));
}, 10, 2);
```

**Parameters:**

- `$term_id` (int) - The term ID
- `$thumbnail_id` (int) - The attachment ID

---

## Constants

### Filterable Constants

These constants can be modified via filters:

```php
// Tablet breakpoint (default: 768px)
add_filter('dynos_breakpoint_tablet', function($breakpoint) {
    return '992px';
});

// Mobile breakpoint (default: 480px)
add_filter('dynos_breakpoint_mobile', function($breakpoint) {
    return '576px';
});

// Maximum grid columns (default: 6)
add_filter('dynos_max_grid_columns', function($max) {
    return 4;
});

// Minimum grid columns (default: 1)
add_filter('dynos_min_grid_columns', function($min) {
    return 2;
});

// Maximum posts per page (default: 1000)
add_filter('dynos_max_posts_per_page', function($max) {
    return 500;
});

// Default excerpt length (default: 20)
add_filter('dynos_default_excerpt_length', function($length) {
    return 30;
});

// Default grid minimum width (default: 280px)
add_filter('dynos_default_grid_min_width', function($width) {
    return '320px';
});

// Maximum FAQs per post (default: 100)
add_filter('dynos_max_faqs_per_post', function($max) {
    return 50;
});
```

---

## Common Use Cases

### Change Post Type Slug

```php
add_filter('dynos_service_cpt_args', function($args) {
    $args['rewrite'] = array('slug' => 'courses');
    return $args;
});

// Don't forget to flush rewrite rules after changing slugs
// Go to Settings → Permalinks and click Save
```

### Add Custom Meta to Query

```php
add_filter('dynos_service_cards_query_args', function($args, $atts) {
    $args['meta_query'] = array(
        'relation' => 'AND',
        array(
            'key' => 'difficulty_level',
            'value' => 'beginner',
            'compare' => '='
        ),
        array(
            'key' => '_thumbnail_id',
            'compare' => 'EXISTS'
        )
    );
    return $args;
}, 10, 2);
```

### Customize Card Appearance

```php
// Change all card colors at once
add_filter('dynos_card_dynamic_css', function($css, $options) {
    $custom_css = "
        .service-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .service-card-title a {
            color: white !important;
        }
        .service-card-link {
            background: white !important;
            color: #667eea !important;
        }
    ";
    return $css . $custom_css;
}, 10, 2);
```

### Add Schema Markup to FAQs

```php
add_filter('dynos_faqs_output', function($output, $faqs, $post_id) {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array()
    );
    
    foreach ($faqs as $faq) {
        $schema['mainEntity'][] = array(
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $faq['answer']
            )
        );
    }
    
    $schema_json = '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
    return $schema_json . $output;
}, 10, 3);
```

---

For more examples and detailed documentation, visit the [plugin repository](https://github.com/techmire-solutions/dynamic-online-services).
