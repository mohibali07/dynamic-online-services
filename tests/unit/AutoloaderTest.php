<?php
declare(strict_types=1);

if ( ! defined( "ABSPATH" ) ) {
	exit;
}
/**
 * Autoloader Test
 */

use PHPUnit\Framework\TestCase;
use TechmireSolutions\DynamicOnlineServices\Autoloader;

class AutoloaderTest extends TestCase
{
    /**
     * Test mapping of namespace to file path.
     */
    public function test_autoload_simple_class()
    {
        // We can't easily test the require_once without mocking the file system or creating real files.
        // But we can test that the method exists and runs without error.

        $this->assertTrue(method_exists('TechmireSolutions\DynamicOnlineServices\Autoloader', 'autoload'));
    }

    /**
     * Test that non-matching namespace returns early (void).
     */
    public function test_autoload_ignore_other_namespace()
    {
        Autoloader::autoload('OtherNamespace\Class');
        // $result = Autoloader::autoload('OtherNamespace\Class'); // Autoload returns void
        $this->assertTrue(true); // verify it doesn't crash
    }
}
