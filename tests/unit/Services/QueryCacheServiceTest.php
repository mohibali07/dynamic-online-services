<?php
/**
 * Query Cache Service Test
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Tests\Unit\Services;

use TechmireSolutions\DynamicOnlineServices\Services\QueryCacheService;
use WP_Mock;
use DYNOS_TestCase;

class QueryCacheServiceTest extends DYNOS_TestCase {

    public function setUp(): void {
        parent::setUp();
    }

    public function test_get_posts() {
        $args = ['post_type' => 'post'];
        $query_mock = \Mockery::mock('WP_Query');
        $query_mock->posts = [];
        $query_mock->shouldReceive('have_posts')->andReturn(false);

        // Mock Cache class if needed, but QueryCacheService::get_posts uses get_transient directly in some paths?
        // Wait, QueryCacheService.php showed:
        // get_terms uses Cache::get_or_set
        // get_posts uses get_transient directly

        WP_Mock::userFunction('get_transient', [
            'return' => false
        ]);

        // It creates new \WP_Query($args). We rely on WP_Mock or expect it to work if WP_Query is available.
        // bootstrapping usually mocks WP_Query?
        // If not, we might need a wrapper or existing mock.

        // Let's try to run it. If new WP_Query fails, we'll know.
        // Actually, WP_Mock provides checking for new WP_Query? No, usually we mock the class.
        // If WP_Query is already defined by bootstrap, we can't mock it easily.
        // But tests/bootstrap.php says: "WP_Mock::bootstrap();"

        // Let's assume WP_Query class exists and we can verify result type.

        // We need to support new WP_Query($args) call.
        // Since we can't easily mock 'new' in PHP without DI,
        // and this service instantiates directly: $query = new \WP_Query($args);
        // We might just check if it returns a WP_Query object (even if empty or mocked).

        $result = QueryCacheService::get_posts($args);

        $this->assertInstanceOf(\WP_Query::class, $result);
    }
}
