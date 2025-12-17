# Phase 3: Array Syntax Conversion Plan

## Overview

Convert all `array()` syntax to modern short `[]` syntax across the codebase.

## Found Instances

- **Total**: 298+ instances of `array()` syntax
- **Scope**: `/includes` directory

## Conversion Strategy

### Priority 1: High-Impact Files

1. Core classes (`class-plugin.php`, `class-autoloader.php`)
2. Helper classes (already using mixed syntax)
3. Services and utilities

### Priority 2: Feature Files

1. FAQs module
2. Post Types
3. Taxonomies
4. Shortcodes
5. Settings

### Priority 3: Supporting Files

1. Meta boxes
2. Scripts/styles includes
3. Helper functions

## Automated Approach

Using `sed` for bulk replacement:

```bash
# Test on single file first
sed -i '' 's/array(/[/g' file.php
sed -i '' 's/)/]/g' # Handle closing - CAREFUL

# Better: Use PHP CS Fixer
vendor/bin/phpcbf --standard=PHPCompatibility --sniffs=Generic.Arrays.DisallowLongArraySyntax
```

## Manual Review Required

Files requiring careful manual conversion:

- Files with nested arrays
- Files with  arrays in comments
- Files with array keywords in strings

## Testing After Conversion

1. Run PHPUnit tests
2. Check for syntax errors: `php -l file.php`
3. Test in browser
4. Check admin functionality

## Rollback Plan

- Git commit before conversion
- Keep backup of modified files
- Test incrementally by directory

---

**Note**: Using automated sed replacement is risky. Better to use PHP-CS-Fixer or manual conversion for accuracy.
