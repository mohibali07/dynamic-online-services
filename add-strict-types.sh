#!/bin/bash
# Script to add declare(strict_types=1) to all PHP files in the includes directory
# that don't already have it

# Find all PHP files in includes/ excluding vendor and tests
find /Users/syedaalin/Documents/dynamic-online-services/includes -name "*.php" -type f | while read file; do
    # Check if file already has declare(strict_types=1)
    if ! grep -q "declare(strict_types=1)" "$file"; then
        # Check if file has namespace declaration
        if grep -q "^namespace " "$file"; then
            # Add declare before namespace
            sed -i.bak '/^namespace /i\
declare(strict_types=1);\
' "$file" && rm "${file}.bak"
            echo "Added strict types to: $file"
        elif grep -q "^<?php" "$file"; then
            # For files without namespace, add after opening tag and docblock
            # This is more complex, so we'll handle these manually
            echo "MANUAL: $file (no namespace)"
        fi
    fi
done
