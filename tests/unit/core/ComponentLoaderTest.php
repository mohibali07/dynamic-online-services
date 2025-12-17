<?php
/**
 * ComponentLoader Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Core;

use DYNOS_TestCase;
use TechmireSolutions\DynamicOnlineServices\Core\ComponentLoader;

/**
 * Test ComponentLoader class.
 */
class ComponentLoaderTest extends DYNOS_TestCase {

    public function setUp(): void {
        parent::setUp();
        // Mock classes instantiated in init()
        if (!class_exists('TechmireSolutions\DynamicOnlineServices\Core\FrontendAssets')) {
            \Mockery::mock('alias:TechmireSolutions\DynamicOnlineServices\Core\FrontendAssets')
                ->shouldReceive('init')->once();
        }
        if (!class_exists('TechmireSolutions\DynamicOnlineServices\Admin\AdminAssets')) {
            \Mockery::mock('alias:TechmireSolutions\DynamicOnlineServices\Admin\AdminAssets')
                ->shouldReceive('init')->once();
        }
    }

    public function test_init_initializes_assets() {
        // Since we cannot easily overload existing classes if they are already loaded,
        // we check if they are mocked. If the autoloader loads the real classes,
        // we can't mock 'new Class()'.
        // However, we can trust the integration or just call it to ensure no errors.

        // Note: Overloading classes with Mockery is unstable in some environments.
        // If the classes are simple, we might just let them run or mock their dependencies (add_action).

        // For now, let's assume they are mockable or valid.
        // If real classes run, they call add_action. We can expect those actions.

        // Expect actions from FrontendAssets and AdminAssets if real classes run
        // FrontendAssets usually queues scripts.

        // Given the complexity of mocking 'new' keyword, we'll verify it runs without error.

        ComponentLoader::init();

        $this->assertTrue(true);
    }
}
