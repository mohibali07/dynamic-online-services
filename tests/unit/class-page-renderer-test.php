<?php
declare(strict_types=1);

if ( ! defined( "ABSPATH" ) ) {
	exit;
}
/**
 * PageRenderer Test
 */

use PHPUnit\Framework\TestCase;
use TechmireSolutions\DynamicOnlineServices\Settings\PageRenderer;

class PageRendererTest extends TestCase
{
    public function test_class_exists()
    {
        $this->assertTrue(class_exists('TechmireSolutions\DynamicOnlineServices\Settings\PageRenderer'));
    }

    public function test_methods_exist()
    {
        $this->assertTrue(method_exists('TechmireSolutions\DynamicOnlineServices\Settings\PageRenderer', 'render'));
        $this->assertTrue(method_exists('TechmireSolutions\DynamicOnlineServices\Settings\PageRenderer', 'render_flush_rewrite_section'));
        $this->assertTrue(method_exists('TechmireSolutions\DynamicOnlineServices\Settings\PageRenderer', 'render_uninstall_settings'));
    }
}
