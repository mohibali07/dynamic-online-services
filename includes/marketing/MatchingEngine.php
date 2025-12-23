<?php
/**
 * Marketing Matching Engine
 *
 * @package Dynamic_Online_Services
 * @subpackage Marketing
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Marketing;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class MatchingEngine
 *
 * core logic to find the best Service for a given context.
 */
class MatchingEngine
{

	/**
	 * Find the best matching services for a given context.
	 *
	 * @param array<string, mixed> $context Context data (title, h1, h2, body, url, post_id).
	 * @param int                  $limit   Max number of matches to return.
	 * @return array<int> Array of Service Post IDs, sorted by relevance.
	 */
	public function find_matches(array $context, int $limit = 5): array
	{
		// Get options
		$options = \TechmireSolutions\DynamicOnlineServices\Helpers\Options::get();
		$threshold = isset($options['marketing_match_threshold']) ? (int) $options['marketing_match_threshold'] : 3;

		// Check cache if post_id is provided
		$post_id = isset($context['post_id']) ? (int) $context['post_id'] : 0;
		if ($post_id > 0) {
			$cache_key = 'dynos_marketing_matches_' . $post_id;
			$cached = get_transient($cache_key);
			if (false !== $cached) {
				return $cached;
			}
		}

		// Normalize context
		$title = isset($context['title']) ? strtolower((string) $context['title']) : '';
		$h1 = isset($context['h1']) ? strtolower((string) $context['h1']) : '';
		$h2 = isset($context['h2']) ? strtolower((string) $context['h2']) : '';
		$body = isset($context['body']) ? strtolower((string) $context['body']) : '';
		$url = isset($context['url']) ? strtolower((string) $context['url']) : '';

		// 1. Get all Services with Keywords
		$services = $this->get_services_with_keywords();

		if (empty($services)) {
			return array();
		}

		$matches = array();
		$stop_words = $this->get_stop_words();

		foreach ($services as $service_id => $keywords) {
			$score = 0;

			foreach ($keywords as $keyword) {
				$keyword = strtolower(trim($keyword));

				// Skip empty or stop words
				if (empty($keyword) || in_array($keyword, $stop_words, true)) {
					continue;
				}

				// Prepare regex pattern for word boundary
				// Escapes the keyword to be safe in regex
				$pattern = '/\b' . preg_quote($keyword, '/') . '\b/i';

				// Exact match in Title (High value)
				if (!empty($title) && preg_match($pattern, $title)) {
					$score += 10;
				}

				// Exact match in H1 (High value)
				if (!empty($h1) && preg_match($pattern, $h1)) {
					$score += 8;
				}

				// Exact match in URL (Medium value) - URL usually has hyphens, so we check simple strpos
				if (!empty($url) && strpos($url, str_replace(' ', '-', $keyword)) !== false) {
					$score += 5;
				}

				// Match in H2 (Medium value)
				if (!empty($h2) && preg_match($pattern, $h2)) {
					$score += 3;
				}

				// Match in Body (Low value) - Check for existence
				if (!empty($body) && preg_match($pattern, $body)) {
					$score += 1;
				}
			}

			// Minimum threshold
			if ($score >= $threshold) {
				$matches[] = array(
					'id' => $service_id,
					'score' => $score,
				);
			}
		}

		// Sort by score descending
		usort($matches, function($a, $b) {
			return $b['score'] <=> $a['score'];
		});

		// Get threshold from options
		$options = \TechmireSolutions\DynamicOnlineServices\Helpers\Options::get();
		$threshold = isset($options['marketing_match_threshold']) ? (int) $options['marketing_match_threshold'] : 3;

		// Filter matches below threshold
		$matches = array_filter($matches, function ($match) use ($threshold) {
			return $match['score'] >= $threshold;
		});

		// Extract IDs and limit
		$result_ids = array_column($matches, 'id');
		$final_result = array_slice($result_ids, 0, $limit);

		// Set cache (12 Hours)
		if ($post_id > 0) {
			set_transient($cache_key, $final_result, 12 * HOUR_IN_SECONDS);
		}

		return $final_result;
	}

	/**
	 * Get stop words.
	 *
	 * @return array<string>
	 */
	private function get_stop_words(): array
	{
		$options = \TechmireSolutions\DynamicOnlineServices\Helpers\Options::get();
		$raw_stop_words = isset($options['marketing_stop_words']) ? $options['marketing_stop_words'] : 'the, and, or, but, is, a, an, in, on, of, for, to, at, by, best, top';

		$stop_words = array_map('trim', explode(',', $raw_stop_words));
		return array_filter($stop_words);
	}

	/**
	 * Get all active services and their keywords.
	 *
	 * @return array<int, array<string>> Array of Service ID => [Keywords]
	 */
	private function get_services_with_keywords(): array
	{
		// Cache this expensive query
		$cache_key = 'dynos_marketing_service_keywords';
		$cached = get_transient($cache_key);
		if (false !== $cached) {
			return $cached;
		}

		// Get option for services slug
		$options = \TechmireSolutions\DynamicOnlineServices\Helpers\Options::get();
		$service_slug = isset($options['service_slug']) ? $options['service_slug'] : 'services';

		$args = array(
			'post_type' => $service_slug,
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'fields' => 'ids',
		);

		$query = new \WP_Query($args);
		$map = array();

		if ($query->have_posts()) {
			foreach ($query->posts as $post_id) {
				$terms = get_the_terms($post_id, 'service_keywords');
				$keywords = array();

				if ($terms && !is_wp_error($terms)) {
					foreach ($terms as $term) {
						$keywords[] = $term->name;
					}
				}

				// Also use the title as a keyword
				$keywords[] = get_the_title($post_id);

				$map[$post_id] = $keywords;
			}
		}

		set_transient($cache_key, $map, HOUR_IN_SECONDS);
		return $map;
	}
}
