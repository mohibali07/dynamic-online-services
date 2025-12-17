<?php
/**
 * Cards Query Service Test
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Tests\Unit\Services;

use TechmireSolutions\DynamicOnlineServices\Services\CardsQueryService;
use WP_Mock;
use DYNOS_TestCase;

/**
 * @runInSeparateProcess
 * @preserveGlobalState disabled
 */
class CardsQueryServiceTest extends DYNOS_TestCase {

    public function setUp(): void {
        parent::setUp();
    }

    public function test_get_query_returns_wp_query() {
        $atts = ['limit' => 10, 'show_pagination' => 'false'];

        // Mock Sanitization class (PostTypes namespace)
        $sanitizationMock = \Mockery::mock('alias:TechmireSolutions\\DynamicOnlineServices\\PostTypes\\Sanitization');
        $sanitizationMock->shouldReceive('sanitize_cpt_settings')
            ->andReturn(['service_slug' => 'services'])
            ->byDefault();

        // Mock Cache class existence and method if it's used
        if (!class_exists('TechmireSolutions\DynamicOnlineServices\Helpers\Cache')) {
            $cacheMock = \Mockery::mock('alias:TechmireSolutions\DynamicOnlineServices\Helpers\Cache');
            // If Cache is triggered, it should return array or something compatible
            $cacheMock->shouldReceive('get_posts')->andReturn([]);
        }

        WP_Mock::onFilter('dynos_max_posts_per_page')
            ->with(100)
            ->reply(100);

        WP_Mock::onFilter('dynos_service_cards_query_args')
            ->with(\Mockery::type('array'), $atts)
            ->reply(function($args) { return $args; });

        // We can't easily mock new WP_Query() without a wrapper or using WP_Mock's intercepts if implemented
        // But WP_Mock usually mocks WP_Query automatically or we can rely on it being available in bootstrapped env
        // The bootstrap.php loads WP_Mock.

        $query = CardsQueryService::get_query($atts);

        $this->assertInstanceOf(\WP_Query::class, $query);
    }


}
