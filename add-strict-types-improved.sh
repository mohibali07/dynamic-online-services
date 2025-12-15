#!/bin/bash
# Improved script to add declare(strict_types=1) to all PHP files
# Handles both namespaced and non-namespaced files

echo "Adding strict types to PHP files..."
count=0

# Find all PHP files in includes/ excluding vendor and tests
find /Users/syedaalin/Documents/dynamic-online-services/includes -name "*.php" -type f | while read file; do
    # Check if file already has declare(strict_types=1)
    if ! grep -q "declare(strict_types=1)" "$file"; then
        
        # Check if file has namespace declaration
        if grep -q "^namespace " "$file"; then
            # Add declare before namespace
            sed -i.bak '/^namespace /i\
declare(strict_types=1);\
\
' "$file" && rm "${file}.bak"
            echo "✓ Added to: $(basename $file)"
            ((count++))
            
        # Check if file has namespace with \r (Windows line endings)
        elif grep -q "^namespace " "$file" 2>/dev/null; then
            sed -i.bak '/^namespace /i\
declare(strict_types=1);\
\
' "$file" && rm "${file}.bak"
            echo "✓ Added to: $(basename $file)"
            ((count++))
            
        else
            # For files without namespace, add after docblock
            # Find the line after the last */ (end of docblock)
            awk '
                /\*\// { docblock_end = NR }
                END { print docblock_end }
            ' "$file" > /tmp/line_num.txt
            
            line_num=$(cat /tmp/line_num.txt)
            
            if [ ! -z "$line_num" ] && [ "$line_num" -gt 0 ]; then
                # Add after docblock
                sed -i.bak "${line_num}a\\
\\
declare(strict_types=1);\\
" "$file" && rm "${file}.bak"
                echo "✓ Added to: $(basename $file) (no namespace)"
                ((count++))
            else
                echo "⚠ Skipped: $(basename $file) (no docblock found)"
            fi
        fi
    fi
done

echo ""
echo "✅ Complete! Added strict types to $count files."
