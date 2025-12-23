<?php
declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Marketing;

use WP_Mock\Tools\TestCase;
use TechmireSolutions\DynamicOnlineServices\Marketing\MatchingEngine;

require_once dirname(__DIR__, 3) . '/includes/marketing/MatchingEngine.php';
// Attempt to load Options class if exists
if (file_exists(dirname(__DIR__, 3) . '/includes/helpers/Options.php')) {
    require_once dirname(__DIR__, 3) . '/includes/helpers/Options.php';
}
if (file_exists(dirname(__DIR__, 3) . '/includes/settings/Defaults.php')) {
    require_once dirname(__DIR__, 3) . '/includes/settings/Defaults.php';
}
// Mock the Options class if it is not loaded, or relies on WP functions we mocked.
// Since Options::get() calls get_option(), and we mock get_option(), we just need to make sure Options class is available or valid.
// But wait, the code calls `\TechmireSolutions\DynamicOnlineServices\Helpers\Options::get()`.
// We should probably define a mock for it or require actual file if it is simple.
if (!class_exists('\TechmireSolutions\DynamicOnlineServices\Helpers\Options')) {
    // Create a stub if file not found easily or to avoid dependency hell
    // But better to just mock the get_option return value assuming Options::get just returns that.

    // Let's assume Options::get uses get_option('dynos_options').
}

class MatchingEngineTest extends TestCase {

    private $transients = [];
    private $captured_transients = [];

	public function setUp(): void {
		parent::setUp();
		\WP_Mock::setUp();

        $this->transients = [
             'dynos_options_cache_version' => false,
             'dynos_options_last_update' => false,
             'dynos_marketing_service_keywords' => [],
        ];
        $this->captured_transients = [];

        // Flexible get_transient
        \WP_Mock::userFunction('get_transient')
            ->andReturnUsing(function($key) {
                return isset($this->transients[$key]) ? $this->transients[$key] : false;
            });

        // Mock Options::get() behavior via get_option
        $mock_options = [
            'marketing_match_threshold' => 3,
            'marketing_stop_words' => 'the, best'
        ];

        /** @var array $options_cast */
        $options_cast = $mock_options;
        \WP_Mock::userFunction('get_option')
            ->with('dynos_options', \WP_Mock\Functions::type('array'))
            ->andReturn($options_cast);

        // Helper functions
        \WP_Mock::userFunction('wp_parse_args')->andReturnArg(0);
        \WP_Mock::userFunction('apply_filters')->andReturnArg(1);

        // Spy on set_transient
        \WP_Mock::userFunction('set_transient')
            ->andReturnUsing(function($key, $value, $expiration) {
                $this->captured_transients[$key] = [
                    'value' => $value,
                    'expiration' => $expiration
                ];
                return true;
            });
	}

	public function tearDown(): void {
		\WP_Mock::tearDown();
		parent::tearDown();
        $this->transients = [];
        $this->captured_transients = [];
	}

	public function test_find_matches_returns_correct_services() {
		// Mock the context
		$context = [
			'title'   => 'The Future of Web Design',
			'h1'      => 'Web Design Trends',
			'body'    => 'We love CSS and HTML.',
			'url'     => 'http://example.com/web-design',
			'post_id' => 123
		];

		// Define Transients
        $this->transients['dynos_marketing_matches_123'] = false;
        $this->transients['dynos_marketing_service_keywords'] = [
			10 => ['design', 'web'],
			20 => ['seo', 'marketing'],
			30 => ['css', 'html'],
			40 => ['future'],
		];

		// Instantiate
		$engine = new MatchingEngine();
		$matches = $engine->find_matches($context);

		$this->assertContains(10, $matches, 'Service 10 should be a match');
		$this->assertEquals(10, $matches[0], 'Service 10 should be the top match');
		$this->assertContains(40, $matches, 'Service 40 should be a match');
		$this->assertNotContains(30, $matches, 'Service 30 should not be a match (score < 3)');

        // Assert set_transient was called
        $this->assertArrayHasKey('dynos_marketing_matches_123', $this->captured_transients);
        $this->assertEquals(12 * 3600, $this->captured_transients['dynos_marketing_matches_123']['expiration']);
	}

	public function test_find_matches_respects_word_boundaries() {
		$context = [
			'title' => 'The Smart Way',
			'post_id' => 1
		];

        $this->transients['dynos_marketing_matches_1'] = false;
        $this->transients['dynos_marketing_service_keywords'] = [
			100 => ['art'],
		];

		$engine = new MatchingEngine();
		$matches = $engine->find_matches($context);

		$this->assertEmpty($matches, 'Should not match "art" inside "Smart"');

        $this->assertArrayHasKey('dynos_marketing_matches_1', $this->captured_transients);
	}

	public function test_find_matches_ignores_stop_words() {
		$context = [
			'title' => 'The Best Service',
			'post_id' => 2
		];

        $this->transients['dynos_marketing_matches_2'] = false;
        $this->transients['dynos_marketing_service_keywords'] = [
			200 => ['the', 'best'],
		];

		$engine = new MatchingEngine();
		$matches = $engine->find_matches($context);

		$this->assertEmpty($matches, 'Should ignore stop words "the" and "best"');

        $this->assertArrayHasKey('dynos_marketing_matches_2', $this->captured_transients);
	}
}
