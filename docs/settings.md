# Plugin Settings Documentation

## Overview

Active controls for `Dynamic Online Services` are managed via the **Dynamic Services** menu in the WordPress Admin Dashboard.

## 1. Post Type & Taxonomy

Configuration for the core URL structure and labels.

- **Service Post Type Slug**: Default `services`. Changing this updates the URL structure (e.g., `/services/my-service`).
- **Service Taxonomy Slug**: Default `service-category`. matches the category base.
- **Menu Position**: Where the menu appears in the admin sidebar.
- **Labels**: Customize "Service", "Services", "Category", etc. to match your business domain (e.g., "Courses", "Classes").

## 2. Hero Section

Controls the appearance of the top hero banner on service archive pages.

- **Colors**:
  - `Hero Title Color`: Default `White`.
  - `Overlay Color`: Default `Black` with `0.5` opacity.
- **Typography**:
  - `Font Family`: Accepts generic families or Google Fonts.
  - `Title Font Size`: Responsive controls for Desktop (`3rem`), Tablet (`2.5rem`), and Mobile (`2rem`).
  - `Description Font Size`: Responsive controls for description text.
- **Dimensions**:
  - `Hero Height`: Default `50vh` (Desktop). Responsive controls available.
  - `Content Padding`: Internal spacing.
  - `Border Radius`: Rounding of internal content box.
- **SEO**:
  - `Rank Math Breadcrumbs`: Toggle to display breadcrumbs if Rank Math is active.

## 3. Service Cards

Controls the grid layout and card design for individual services.

- **Design**:
  - `Background Color`: Default `#6A4B3F`.
  - `Title/Text Colors`: Fully customizable.
  - `Border Radius`: Default `18px`.
  - `Box Shadow`: Configurable CSS shadow.
- **Layout**:
  - `Card Height`: Fixed height for uniformity (Default `400px`).
  - `Grid Columns`: Default `3`.
  - `Grid Gap`: Control row (`40px`) and column (`20px`) spacing.
  - `Min Width`: `280px` (prevents cards from getting too squished).
- **Interactions**:
  - `Hover Transform`: Default `translateY(-5px)`.
  - `Transition Speed`: Smooth animation control (`0.2s`).
- **Button**:
  - Custom colors for default and hover states.

## 4. FAQ Accordion

*Settings located in FAQ tab.*

- Controls colors and behavior for the FAQ section on service pages.

## 5. WhatsApp Integration

*Settings located in WhatsApp tab.*

- Configure the floating WhatsApp button, default message, and position.

## 6. Advanced

- **Custom CSS**: Add override styles.
- **Scripts**: Enqueue custom JS.
