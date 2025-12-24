<?php
/**
 * Marketing REST Controller
 *
 * @package Dynamic_Online_Services
 * @subpackage Api
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Api;

use TechmireSolutions\DynamicOnlineServices\Marketing\MatchingEngine;
use TechmireSolutions\DynamicOnlineServices\Shortcodes\MarketingWidgetShortcode;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Class MarketingController
 */
class MarketingController
{

	/**
	 * Namespace.
	 *
	 * @var string
	 */
	private const NAMESPACE = 'dynos/v1';

	/**
	 * Resource base.
	 *
	 * @var string
	 */
	private const REST_BASE = 'widget';

	/**
	 * Init API.
	 *
	 * @return void
	 */
	public static function init(): void
	{
		add_action('rest_api_init', array(__CLASS__, 'register_routes'));
	}

	/**
	 * Register routes.
	 *
	 * @return void
	 */
	public static function register_routes(): void
	{
		register_rest_route(
			self::NAMESPACE,
			'/' . self::REST_BASE,
			array(
				array(
					'methods' => \WP_REST_Server::READABLE,
					'callback' => array(__CLASS__, 'get_widget'),
					'permission_callback' => '__return_true', // Public endpoint
					'args' => array(
						'title' => array(
							'default' => '',
							'sanitize_callback' => 'sanitize_text_field',
						),
						'h1' => array(
							'default' => '',
							'sanitize_callback' => 'sanitize_text_field',
						),
						'url' => array(
							'default' => '',
							'sanitize_callback' => 'esc_url_raw',
						),
					),
				),
			)
		);
	}

	/**
	 * Get widget HTML.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response Response object.
	 */
	public static function get_widget(\WP_REST_Request $request): \WP_REST_Response
	{
		$params = $request->get_params();

		$context = array(
			'title' => $params['title'],
			'h1' => $params['h1'],
			'url' => $params['url'],
			'body' => '', // API typically won't send full body text
		);

		$engine = new MatchingEngine();
		$match_ids = $engine->find_matches($context, 5);

		if (empty($match_ids)) {
			return new \WP_REST_Response(
				array(
					'success' => true,
					'matches' => array(),
					'html' => '',
				),
				200
			);
		}

		ob_start();
		if (count($match_ids) === 1) {
			MarketingWidgetShortcode::render_promo_box($match_ids[0]);
		} else {
			MarketingWidgetShortcode::render_carousel($match_ids);
		}
		$html = ob_get_clean();

		return new \WP_REST_Response(
			array(
				'success' => true,
				'matches' => $match_ids,
				'html' => $html,
			),
			200
		);
	}
}
