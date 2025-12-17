<?php
/**
 * Hero Renderer Test
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Tests\Unit\Renderers;

use TechmireSolutions\DynamicOnlineServices\Renderers\HeroRenderer;
use WP_Mock;
use DYNOS_TestCase;

class HeroRendererTest extends DYNOS_TestCase {

    public function setUp(): void {
        parent::setUp();
    }

    public function test_render() {
        $data = [
            'image_url' => 'http://example.com/img.jpg',
            'height' => '50vh',
            'title_color' => '#FFFFFF',
            'font_family' => 'sans-serif',
            'title' => 'Hero Title',
            'description' => 'Hero Description',
            'data_attribute' => 'data-test',
            'data_value' => 'value'
        ];

        // Mock WP functions
        WP_Mock::userFunction('wp_parse_args', [
            'return' => function($args, $defaults) { return array_merge($defaults, $args); }
        ]);
        WP_Mock::userFunction('esc_url', [
            'return' => function($url) { return $url; }
        ]);
        WP_Mock::userFunction('esc_html', [
            'return' => function($text) { return $text; }
        ]);
        WP_Mock::userFunction('esc_attr', [
            'return' => function($text) { return $text; }
        ]);
        WP_Mock::userFunction('wp_kses_post', [
            'return' => function($text) { return $text; }
        ]);
        WP_Mock::userFunction('sanitize_key', [
            'return' => function($key) { return $key; }
        ]);

        $renderer = new HeroRenderer();
        $output = $renderer->render($data);

        $this->assertStringContainsString('Hero Title', $output);
        $this->assertStringContainsString('Hero Description', $output);
        $this->assertStringContainsString('category-hero-container', $output);
    }
}
