<?php
/**
 * FAQ Cache Service Test
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Tests\Unit\Services;

use TechmireSolutions\DynamicOnlineServices\Services\FaqCacheService;
use WP_Mock;
use DYNOS_TestCase;

class FaqCacheServiceTest extends DYNOS_TestCase {

    public function setUp(): void {
        parent::setUp();
    }

    public function test_get_cached_faqs() {
        $post_id = 123;
        $faqs = [['question' => 'Q', 'answer' => 'A']];

        // Cache class handles get_transient. We mock get_transient to simulate cache hit.
        WP_Mock::userFunction('get_transient', [
            'args' => ['dynos_faqs_' . $post_id],
            'return' => $faqs
        ]);

        // Also mock get_post_meta in case get_transient fallback (from bootstrap) causes cache miss behavior
        WP_Mock::userFunction('get_post_meta', [
            'args' => [$post_id, 'dynos_faqs', true],
            'return' => $faqs
        ]);

        $result = FaqCacheService::get($post_id);

        $this->assertEquals($faqs, $result);
    }
}
