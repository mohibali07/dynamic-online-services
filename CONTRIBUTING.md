# Contributing to Dynamic Online Services

Thank you for your interest in contributing to Dynamic Online Services! This document provides guidelines and instructions for contributing to the project.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Setup](#development-setup)
- [Coding Standards](#coding-standards)
- [Testing](#testing)
- [Pull Request Process](#pull-request-process)
- [Reporting Bugs](#reporting-bugs)
- [Suggesting Enhancements](#suggesting-enhancements)

## Code of Conduct

This project follows WordPress community standards. Be respectful, inclusive, and constructive in all interactions.

## Getting Started

### Prerequisites

- **PHP**: 8.3 or higher
- **WordPress**: 6.9 or higher
- **Node.js**: 18.x or higher
- **Composer**: 2.x or higher
- **Git**: Latest version

### Fork and Clone

1. Fork the repository on GitHub
2. Clone your fork locally:

```bash
git clone https://github.com/YOUR-USERNAME/dynamic-online-services.git
cd dynamic-online-services
```

3. Add the upstream repository:

```bash
git remote add upstream https://github.com/techmire-solutions/dynamic-online-services.git
```

## Development Setup

### Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### Build Assets

```bash
# Development build with watch mode
npm start

# Production build
npm run build
```

### Run Tests

```bash
# PHP unit tests
composer test

# PHP unit tests with coverage
composer test:coverage

# E2E tests
npm run test:e2e

# Linting
composer lint
npm run lint:js
npm run lint:css
```

## Coding Standards

### PHP Standards

We follow **WordPress Coding Standards** with modern PHP enhancements:

#### 1. Strict Typing

All PHP files must declare strict types:

```php
<?php
declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\YourNamespace;

if (!defined('ABSPATH')) {
    exit;
}
```

#### 2. Namespacing

All classes must be namespaced:

```php
namespace TechmireSolutions\DynamicOnlineServices\ComponentName;
```

#### 3. Type Declarations

Use type declarations for all parameters and return types:

```php
public function get_posts(int $limit, string $orderby = 'date'): array
{
    // Implementation
}
```

#### 4. File Naming

- **Classes**: `class-class-name.php` (kebab-case with `class-` prefix)
- **Interfaces**: `interface-interface-name.php` (kebab-case with `interface-` prefix)
- **Traits**: `trait-trait-name.php` (kebab-case with `trait-` prefix)
- **All lowercase**: Linux compatibility

**Example:**

```
class-cards-query-service.php  ✅
Class-CardsQueryService.php    ❌
CardsQueryService.php          ❌
```

#### 5. Security

**Always sanitize input:**

```php
$category = sanitize_text_field($_POST['category']);
$limit = absint($_POST['limit']);
```

**Always escape output:**

```php
echo esc_html($title);
echo esc_url($link);
echo esc_attr($class);
```

**Check capabilities:**

```php
if (!current_user_can('manage_options')) {
    wp_die(__('Unauthorized', 'dynamic-online-services'));
}
```

**Verify nonces:**

```php
if (!wp_verify_nonce($_POST['nonce'], 'action_name')) {
    wp_die(__('Security check failed', 'dynamic-online-services'));
}
```

#### 6. ABSPATH Check

Every PHP file must start with:

```php
if (!defined('ABSPATH')) {
    exit;
}
```

### JavaScript Standards

We use **@wordpress/scripts** for JavaScript linting and formatting:

```bash
# Check JavaScript
npm run lint:js

# Auto-fix JavaScript
npm run format
```

**Key points:**

- Use modern ES6+ syntax
- Follow WordPress JavaScript coding standards
- Use WordPress components for UI (`@wordpress/components`)
- Prefer functional components with hooks

### CSS Standards

We use **container queries** for responsive design:

```css
/* Define container */
.component-wrapper {
    container-type: inline-size;
    container-name: component;
}

/* Use container queries */
@container component (min-width: 768px) {
    .component-item {
        display: flex;
    }
}
```

**Key points:**

- Mobile-first approach
- Use CSS variables for theming
- Minimum 44px touch targets
- No hover-only functionality
- Support `prefers-reduced-motion`

### Documentation Standards

#### PHPDoc Blocks

All functions and methods must have PHPDoc blocks:

```php
/**
 * Get service cards query.
 *
 * @since 1.1.0
 * @param array $atts Shortcode attributes.
 * @return WP_Query Query object.
 */
public function get_query(array $atts): WP_Query
{
    // Implementation
}
```

#### Inline Comments

Use inline comments for complex logic:

```php
// FIX: Enforce maximum posts per page to prevent memory exhaustion
if (-1 === $limit || $limit > DYNOS_MAX_POSTS_PER_PAGE) {
    $limit = DYNOS_MAX_POSTS_PER_PAGE;
}
```

## Testing

### Writing Tests

#### PHPUnit Tests

Create tests in `tests/unit/`:

```php
<?php
declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Tests;

use PHPUnit\Framework\TestCase;

class MyFeatureTest extends TestCase
{
    public function test_feature_works(): void
    {
        $result = my_function();
        $this->assertEquals('expected', $result);
    }
}
```

#### E2E Tests

Create tests in `tests/e2e/`:

```typescript
import { test, expect } from '@playwright/test';

test('should display service cards', async ({ page }) => {
    await page.goto('/courses/');
    await expect(page.locator('.service-card')).toBeVisible();
});
```

### Running Tests

```bash
# Run all PHP tests
composer test

# Run specific test file
vendor/bin/phpunit tests/unit/MyFeatureTest.php

# Run with coverage
composer test:coverage

# Run E2E tests
npm run test:e2e

# Run E2E in UI mode
npx playwright test --ui
```

## Pull Request Process

### 1. Create a Branch

Create a descriptive branch name:

```bash
git checkout -b feature/add-course-rating
git checkout -b fix/card-layout-mobile
git checkout -b docs/update-readme
```

**Branch naming:**

- `feature/` - New features
- `fix/` - Bug fixes
- `docs/` - Documentation changes
- `refactor/` - Code refactoring
- `test/` - Test additions/changes

### 2. Make Changes

- Follow coding standards
- Write tests for new features
- Update documentation
- Keep commits atomic and well-described

### 3. Commit Messages

Use clear, descriptive commit messages:

```bash
git commit -m "Add course rating system with star display"
git commit -m "Fix mobile layout for service cards on iOS"
git commit -m "Update README with new shortcode examples"
```

**Format:**

```
<type>: <subject>

<body>

<footer>
```

**Example:**

```
feat: Add course rating system

- Add rating meta box to course edit screen
- Display star rating on service cards
- Add filter for customizing rating display

Closes #123
```

### 4. Test Your Changes

```bash
# Run all tests
composer test
npm run test:e2e

# Run linters
composer lint
npm run lint:js
npm run lint:css

# Build production assets
npm run build
```

### 5. Push and Create PR

```bash
git push origin feature/add-course-rating
```

Then create a Pull Request on GitHub with:

- **Clear title**: "Add course rating system"
- **Description**: What changes were made and why
- **Screenshots**: For UI changes
- **Testing steps**: How to test the changes
- **Related issues**: Link to related issues

### PR Checklist

- [ ] Code follows WordPress coding standards
- [ ] All tests pass
- [ ] New features have tests
- [ ] Documentation is updated
- [ ] No linting errors
- [ ] Commits are well-described
- [ ] PR description is clear

## Reporting Bugs

### Before Submitting

1. Check existing issues
2. Test with latest version
3. Disable other plugins to isolate the issue
4. Test with a default WordPress theme

### Bug Report Template

```markdown
**Describe the bug**
A clear description of what the bug is.

**To Reproduce**
Steps to reproduce:
1. Go to '...'
2. Click on '...'
3. See error

**Expected behavior**
What you expected to happen.

**Screenshots**
If applicable, add screenshots.

**Environment:**
- WordPress version:
- PHP version:
- Plugin version:
- Theme:
- Browser:

**Additional context**
Any other relevant information.
```

## Suggesting Enhancements

### Enhancement Request Template

```markdown
**Is your feature request related to a problem?**
A clear description of the problem.

**Describe the solution you'd like**
A clear description of what you want to happen.

**Describe alternatives you've considered**
Alternative solutions or features you've considered.

**Additional context**
Any other context, mockups, or examples.
```

## Development Workflow

### Typical Workflow

1. **Sync with upstream:**

   ```bash
   git fetch upstream
   git checkout main
   git merge upstream/main
   ```

2. **Create feature branch:**

   ```bash
   git checkout -b feature/my-feature
   ```

3. **Develop and test:**

   ```bash
   npm start  # Watch mode for assets
   composer test  # Run tests
   ```

4. **Commit changes:**

   ```bash
   git add .
   git commit -m "feat: Add my feature"
   ```

5. **Push and create PR:**

   ```bash
   git push origin feature/my-feature
   ```

### Code Review Process

1. Maintainer reviews code
2. Automated tests run
3. Feedback provided if needed
4. Changes requested or approved
5. Merged into main branch

## Questions?

If you have questions:

- Open an issue for discussion
- Check existing documentation
- Review closed issues for similar questions

## License

By contributing, you agree that your contributions will be licensed under the GPL v2 or later license.

---

**Thank you for contributing to Dynamic Online Services!** 🎉
