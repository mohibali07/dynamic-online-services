<?php
/**
 * Permalink Handler
 *
 * Handles custom permalink structure for service posts with category hierarchy.
 *
 * @package Dynamic_Online_Services
 * @subpackage Post_Types
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Replace the %services_category% placeholder in post permalink structure
 * with the actual category hierarchy.
 *
 * @since 1.1.0
 * @param string  $post_link The post permalink.
 * @param WP_Post $post      The post object.
 * @return string Modified post permalink.
 */
function doc_service_permalink_structure($post_link, $post): string {
    // Validate post object
    $post = doc_validate_post_object($post, 'service');
    if (!$post) {
        return $post_link;
    }

    if (false === strpos($post_link, '%services_category%')) {
        return $post_link;
    }

    // Allow filtering before processing
    $post_link = apply_filters('doc_service_permalink_before_process', $post_link, $post);

    $terms = wp_get_object_terms(
        $post->ID,
        'services_category',
        array(
            'orderby' => 'parent',
            'order'   => 'ASC',
        )
    );

    if (empty($terms) || is_wp_error($terms)) {
        // Fallback for posts with no categories assigned, removes the placeholder
        $post_link = str_replace('%services_category%/', '', $post_link);
        // Fire action for debugging
        if (is_wp_error($terms) && defined('WP_DEBUG') && WP_DEBUG) {
            do_action('doc_permalink_term_error', $terms, $post->ID);
        }
        return $post_link;
    }

    $term_path_parts = array();
    $current_term    = $terms[0]; // Use the first assigned term

    // Validate current term
    $current_term = doc_validate_term_object($current_term, 'services_category');
    if (!$current_term) {
        $post_link = str_replace('%services_category%/', '', $post_link);
        return $post_link;
    }

    // Build category hierarchy path
    $term_path = doc_build_category_hierarchy_path($current_term);

    $post_link = str_replace('%services_category%', $term_path, $post_link);

    // Allow filtering after processing
    return apply_filters('doc_service_permalink_after_process', $post_link, $post, $term_path);
}
add_filter('post_type_link', 'doc_service_permalink_structure', 10, 2);

/**
 * Build category hierarchy path for permalink.
 *
 * @since 1.1.0
 * @param WP_Term $current_term Current term object.
 * @return string Category path (e.g., 'parent/child/term').
 */
function doc_build_category_hierarchy_path($current_term): string {
    $term_path_parts = array();
    
    // Climb up the hierarchy to get all parent slugs
    // This builds the full category path for hierarchical permalinks
    $parent_id = $current_term->parent;
    $max_depth = DOC_MAX_TAXONOMY_DEPTH; // Prevent infinite loops in case of circular references
    $depth = 0;
    $visited_ids = array($current_term->term_id); // Track visited terms to detect circular references
    
    // Check for self-reference (term's parent is itself)
    if ($parent_id === $current_term->term_id) {
        doc_log_error(
            sprintf(
                'Self-reference detected in taxonomy hierarchy for term ID %d. Term is its own parent.',
                $current_term->term_id
            ),
            'warning',
            array('term_id' => $current_term->term_id)
        );
        do_action('doc_permalink_self_reference', $current_term->term_id);
        // Use term itself without parent path
        $parent_id = 0;
    }
    
    while (0 !== $parent_id && $depth < $max_depth) {
        // Check for circular reference
        if (in_array($parent_id, $visited_ids, true)) {
            doc_log_error(
                sprintf(
                    'Circular reference detected in taxonomy hierarchy for term ID %d. Parent ID %d was already visited.',
                    $current_term->term_id,
                    $parent_id
                ),
                'warning',
                array(
                    'term_id' => $current_term->term_id,
                    'parent_id' => $parent_id,
                    'visited_ids' => $visited_ids,
                )
            );
            do_action('doc_permalink_circular_reference', $current_term->term_id, $parent_id, $visited_ids);
            break;
        }
        
        $visited_ids[] = $parent_id;
        $parent_term = get_term($parent_id, 'services_category');
        
        if (is_wp_error($parent_term) || !$parent_term) {
            if (is_wp_error($parent_term) && defined('WP_DEBUG') && WP_DEBUG) {
                do_action('doc_permalink_parent_term_error', $parent_term, $parent_id);
            }
            break;
        }
        
        // Add parent to the beginning of the path array
        array_unshift($term_path_parts, $parent_term->slug);
        $parent_id = $parent_term->parent;
        $depth++;
    }
    
    // Log warning if max depth was reached (potential infinite loop prevented)
    if ($depth >= $max_depth) {
        doc_log_error(
            sprintf(
                'Maximum hierarchy depth (%d) reached for term ID %d. Possible circular reference in taxonomy hierarchy.',
                $max_depth,
                $current_term->term_id
            ),
            'warning',
            array(
                'term_id' => $current_term->term_id,
                'max_depth' => $max_depth,
                'depth' => $depth,
            )
        );
        do_action('doc_permalink_max_depth_reached', $current_term->term_id, $max_depth);
    }

    // Add the term itself to the end of the path
    $term_path_parts[] = $current_term->slug;
    $term_path         = implode('/', $term_path_parts);
    
    return $term_path;
}

