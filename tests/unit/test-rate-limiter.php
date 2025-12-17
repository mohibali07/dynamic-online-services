<?php
/**
 * Unit Tests for RateLimiter Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

use TechmireSolutions\DynamicOnlineServices\Helpers\RateLimiter;

/**
 * Test case for RateLimiter helper class.
 *
 * @covers \TechmireSolutions\DynamicOnlineServices\Helpers\RateLimiter
 */
class RateLimiterTest extends DYNOS_TestCase {

	/**
	 * Test rate limit check allows within limit.
	 */
	public function test_check_allows_within_limit(): void {
		$action = 'test_action';
		$user_id = 1;
		$max_attempts = 5;
		$time_window = 60;

		// Mock get_transient to return 3 attempts (under limit)
		\WP_Mock::userFunction('get_transient', [
			'times' => 1,
			'return' => 3,
		]);

		// Should call set_transient to increment
		\WP_Mock::userFunction('set_transient', [
			'times' => 1,
			'return' => true,
		]);

		// Mock filters
		\WP_Mock::onFilter('dynos_disable_rate_limiting')->with(false)->reply(false);
		\WP_Mock::onFilter("dynos_disable_rate_limiting_{$action}")->with(false)->reply(false);

		$result = RateLimiter::check($action, $user_id, $max_attempts, $time_window);
		$this->assertTrue($result);
	}

	/**
	 * Test rate limit check blocks when limit exceeded.
	 */
	public function test_check_blocks_when_limit_exceeded(): void {
		$action = 'test_action';
		$user_id = 1;
		$max_attempts = 5;
		$time_window = 60;

		// Mock get_transient to return 5 attempts (at limit)
		\WP_Mock::userFunction('get_transient', [
			'times' => 1,
			'return' => 5,
		]);

		// Mock filters
		\WP_Mock::onFilter('dynos_disable_rate_limiting')->with(false)->reply(false);
		\WP_Mock::onFilter("dynos_disable_rate_limiting_{$action}")->with(false)->reply(false);

		// Should fire rate limit exceeded action
		\WP_Mock::expectAction('dynos_rate_limit_exceeded', $action, $user_id, 5, $max_attempts);

		$result = RateLimiter::check($action, $user_id, $max_attempts, $time_window);
		$this->assertFalse($result);
	}

	/**
	 * Test rate limiting can be bypassed via filter.
	 */
	public function test_check_can_be_bypassed_via_global_filter(): void {
		$action = 'test_action';
		$user_id = 1;

		// Mock filter to return true (disable all rate limiting)
		\WP_Mock::onFilter('dynos_disable_rate_limiting')->with(false)->reply(true);

		$result = RateLimiter::check($action, $user_id);
		$this->assertTrue($result);
	}

	/**
	 * Test rate limiting can be bypassed for specific action.
	 */
	public function test_check_can_be_bypassed_via_action_filter(): void {
		$action = 'test_action';
		$user_id = 1;

		// Mock filters
		\WP_Mock::onFilter('dynos_disable_rate_limiting')->with(false)->reply(false);
		\WP_Mock::onFilter("dynos_disable_rate_limiting_{$action}")->with(false)->reply(true);

		$result = RateLimiter::check($action, $user_id);
		$this->assertTrue($result);
	}

	/**
	 * Test reset clears rate limit for user action.
	 */
	public function test_reset_clears_limit(): void {
		$action = 'test_action';
		$user_id = 1;

		// Mock delete_transient
		\WP_Mock::userFunction('delete_transient', [
			'times' => 1,
			'return' => true,
		]);

		$result = RateLimiter::reset($action, $user_id);
		$this->assertTrue($result);
	}

	/**
	 * Test get_remaining returns correct count.
	 */
	public function test_get_remaining_returns_correct_count(): void {
		$action = 'test_action';
		$user_id = 1;
		$max_attempts = 10;

		// Mock get_transient to return 3 attempts
		\WP_Mock::userFunction('get_transient', [
			'times' => 1,
			'return' => 3,
		]);

		$remaining = RateLimiter::get_remaining($action, $user_id, $max_attempts);
		$this->assertSame(7, $remaining);
	}

	/**
	 * Test get_remaining returns zero when limit exceeded.
	 */
	public function test_get_remaining_returns_zero_when_exceeded(): void {
		$action = 'test_action';
		$user_id = 1;
		$max_attempts = 5;

		// Mock get_transient to return 10 attempts (over limit)
		\WP_Mock::userFunction('get_transient', [
			'times' => 1,
			'return' => 10,
		]);

		$remaining = RateLimiter::get_remaining($action, $user_id, $max_attempts);
		$this->assertSame(0, $remaining);
	}

	/**
	 * Test clear_all removes all rate limits.
	 */
	public function test_clear_all_removes_all_limits(): void {
		// Mock global $wpdb
		global $wpdb;
		$wpdb = \Mockery::mock('wpdb');
		$wpdb->options = 'wp_options';

		// Expect prepare to be called
		$wpdb->shouldReceive('prepare')
			->once()
			->andReturn("DELETE FROM wp_options WHERE option_name LIKE '_transient_dynos_rate_limit_%'");

		$wpdb->shouldReceive('esc_like')
			->once()
			->with('_transient_dynos_rate_limit_')
			->andReturn('_transient_dynos_rate_limit_');

		// Expect query to be called
		$wpdb->shouldReceive('query')
			->once()
			->andReturn(5);

		// Should fire action
		\WP_Mock::expectAction('dynos_rate_limits_cleared');

		RateLimiter::clear_all();
	}

	/**
	 * Test transient key generation is consistent.
	 */
	public function test_transient_key_generation(): void {
		// This tests the private method indirectly
		$action = 'faq_save';
		$user_id = 123;

		// Mock sanitize_key
		\WP_Mock::userFunction('sanitize_key', [
			'times' => 1,
			'args' => [$action],
			'return' => $action,
		]);

		// Mock transient operations - they should use consistent key
		\WP_Mock::userFunction('get_transient', [
			'times' => 1,
			'return' => false,
		]);

		\WP_Mock::userFunction('set_transient', [
			'times' => 1,
			'with' => [
				\Mockery::type('string'), // Key should be string
				1, // Incremented count
				60, // Time window
			],
			'return' => true,
		]);

		\WP_Mock::onFilter('dynos_disable_rate_limiting')->with(false)->reply(false);
		\WP_Mock::onFilter("dynos_disable_rate_limiting_{$action}")->with(false)->reply(false);

		RateLimiter::check($action, $user_id, 10, 60);
	}
}
