<?php
/**
 * IntegrationInitializer Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Core;

use DYNOS_TestCase;
use TechmireSolutions\DynamicOnlineServices\Core\IntegrationInitializer;

/**
 * Test IntegrationInitializer class.
 */
class IntegrationInitializerTest extends DYNOS_TestCase {

    public function test_init_checks_integrations() {
        // Just verify it runs without error.
        // Integrations logic usually checks class_exists.

        IntegrationInitializer::init();

        $this->assertTrue(true);
    }
}
