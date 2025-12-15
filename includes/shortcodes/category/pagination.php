<?php
/**
 * Category Shortcode Pagination
 *
 * Handles pagination HTML generation for category shortcode.
 *
 * @package Dynamic_Online_Services
 * @subpackage Shortcodes
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Default number of pages to show on each side of current page in pagination.
 *
 * @since 1.1.0
 */
if (!defined('DYNOS_PAGINATION_RANGE')) {
	define('DYNOS_PAGINATION_RANGE', 2);
}

/**
 * Generate pagination HTML.
 *
 * @since 1.1.0
 * @param int $current_page Current page number.
 * @param int $total_pages  Total number of pages.
 * @return string Pagination HTML.
 */
function dynos_get_pagination_html($current_page, $total_pages): string
{
	if ($total_pages <= 1) {
		return '';
	}

	$current_page = absint($current_page);
	$total_pages = absint($total_pages);

	// Get base term link with error handling
	$queried_object = get_queried_object();
	if (!$queried_object) {
		return '';
	}

	$base_term_link = get_term_link($queried_object);
	if (is_wp_error($base_term_link)) {
		dynos_log_error(
			sprintf(
				'Failed to get term link for pagination. Term ID: %d, Error: %s',
				isset($queried_object->term_id) ? $queried_object->term_id : 0,
				$base_term_link->get_error_message()
			),
			'warning',
			array(
				'term_id' => isset($queried_object->term_id) ? $queried_object->term_id : 0,
				'error_code' => $base_term_link->get_error_code(),
			)
		);
		do_action('dynos_pagination_term_link_error', $queried_object, $base_term_link);
		return '';
	}

	// Build pagination links
	ob_start();
	?>
	<nav class="sos-pagination" role="navigation"
		aria-label="<?php esc_attr_e('Pagination', 'dynamic-online-services'); ?>">
		<ul class="sos-pagination-list">
			<?php
			// Previous page link
			if ($current_page > 1) {
				$prev_url = add_query_arg('paged', $current_page - 1, $base_term_link);
				printf(
					'<li class="sos-pagination-item sos-pagination-prev"><a href="%s" aria-label="%s">%s</a></li>',
					esc_url($prev_url),
					esc_attr__('Previous page', 'dynamic-online-services'),
					esc_html__('« Previous', 'dynamic-online-services')
				);
			}

			// Page number links
			$range = DYNOS_PAGINATION_RANGE; // Number of pages to show on each side of current page
			$start = max(1, $current_page - $range);
			$end = min($total_pages, $current_page + $range);

			// Show first page and ellipsis if needed
			if ($start > 1) {
				$first_url = add_query_arg('paged', 1, $base_term_link);
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Safe integer output
				printf(
					'<li class="sos-pagination-item"><a href="%s">%d</a></li>',
					esc_url($first_url),
					1
				);
				if ($start > 2) {
					echo '<li class="sos-pagination-item sos-pagination-ellipsis"><span>…</span></li>';
				}
			}

			// Show page range
			for ($i = $start; $i <= $end; $i++) {
				if ($i === $current_page) {
					// Safe integer output with absint
					printf(
						'<li class="sos-pagination-item sos-pagination-current"><span aria-current="page">%d</span></li>',
						absint($i)
					);
				} else {
					$page_url = add_query_arg('paged', $i, $base_term_link);
					// Safe integer output with absint
					printf(
						'<li class="sos-pagination-item"><a href="%s">%d</a></li>',
						esc_url($page_url),
						absint($i)
					);
				}
			}

			// Show last page and ellipsis if needed
			if ($end < $total_pages) {
				if ($end < $total_pages - 1) {
					echo '<li class="sos-pagination-item sos-pagination-ellipsis"><span>…</span></li>';
				}
				$last_url = add_query_arg('paged', $total_pages, $base_term_link);
				// Safe integer output with absint
				printf(
					'<li class="sos-pagination-item"><a href="%s">%d</a></li>',
					esc_url($last_url),
					absint($total_pages)
				);
			}

			// Next page link
			if ($current_page < $total_pages) {
				$next_url = add_query_arg('paged', $current_page + 1, $base_term_link);
				printf(
					'<li class="sos-pagination-item sos-pagination-next"><a href="%s" aria-label="%s">%s</a></li>',
					esc_url($next_url),
					esc_attr__('Next page', 'dynamic-online-services'),
					esc_html__('Next »', 'dynamic-online-services')
				);
			}
			?>
		</ul>
	</nav>
	<?php
	return ob_get_clean();
}
