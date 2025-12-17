<?php
/**
 * Sanitization Functions Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit;

use WP_Mock\Tools\TestCase;
use TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization as SanitizationHelper;

/**
 * Test sanitization helper functions.
 */
class SanitizationTest extends TestCase
{
    /**
     * Set up test environment.
     */
    public function setUp(): void
    {
        parent::setUp();
        \WP_Mock::setUp();

        // Load the sanitization functions
        // Updated to point to new Class file location
        require_once dirname(__DIR__, 2) . '/includes/helpers/class-sanitization.php';
        require_once dirname(__DIR__, 2) . '/tests/stubs.php';
    }

    /**
     * Tear down test environment.
     */
    public function tearDown(): void
    {
        \WP_Mock::tearDown();
        parent::tearDown();
    }

    /**
     * Test CSS dimension sanitization with valid values.
     */
    public function test_sanitize_css_dimension_valid_values(): void
    {
        $this->assertEquals('20px', SanitizationHelper::css_dimension('20px'));
        $this->assertEquals('1.5rem', SanitizationHelper::css_dimension('1.5rem'));
        $this->assertEquals('100%', SanitizationHelper::css_dimension('100%'));
        $this->assertEquals('50vh', SanitizationHelper::css_dimension('50vh'));
        $this->assertEquals('auto', SanitizationHelper::css_dimension('auto'));
    }

    /**
     * Test CSS dimension sanitization with invalid values.
     */
    public function test_sanitize_css_dimension_invalid_values(): void
    {
        $this->assertEquals('scriptalert1script', SanitizationHelper::css_dimension('<script>alert(1)</script>'));
        $this->assertEquals('javascriptvoid0', SanitizationHelper::css_dimension('javascript:void(0)'));
        $this->assertEquals('', SanitizationHelper::css_dimension(''));
    }

    /**
     * Test CSS transform sanitization.
     */
    public function test_sanitize_css_transform(): void
    {
        $this->assertStringContainsString('translateX', SanitizationHelper::css_transform('translateX(10px)'));
        $this->assertStringContainsString('rotate', SanitizationHelper::css_transform('rotate(45deg)'));
        $this->assertEquals('script', SanitizationHelper::css_transform('<script>'));
        $this->assertEquals('', SanitizationHelper::css_transform(''));
    }

    /**
     * Test CSS box-shadow sanitization.
     */
    public function test_sanitize_css_box_shadow(): void
    {
        $shadow = '0 4px 6px rgba(0,0,0,0.1)';
        $result = SanitizationHelper::css_box_shadow($shadow);
        $this->assertNotEmpty($result);

        $this->assertEquals('', SanitizationHelper::css_box_shadow('none'));
        $this->assertEquals('', SanitizationHelper::css_box_shadow(''));
    }

    /**
     * Test CSS value escaping prevents injection.
     */
    public function test_escape_css_value_prevents_injection(): void
    {
        $this->assertEquals('', SanitizationHelper::escape_css_value('expression(alert(1))'));
        $this->assertEquals('', SanitizationHelper::escape_css_value('javascript:void(0)'));
        $this->assertEquals('', SanitizationHelper::escape_css_value('url(javascript:alert(1))'));
        $this->assertEquals('alert(1)', SanitizationHelper::escape_css_value('<script>alert(1)</script>'));
    }

    /**
     * Test CSS value escaping allows safe values.
     */
    public function test_escape_css_value_allows_safe_values(): void
    {
        $this->assertEquals('20px', SanitizationHelper::escape_css_value('20px', 'width'));
        $this->assertNotEmpty(SanitizationHelper::escape_css_value('translateX(10px)', 'transform'));
    }

    /**
     * Test post ID validation.
     */
    public function test_validate_post_id(): void
    {
        \WP_Mock::userFunction('get_post')
            ->withAnyArgs()
            ->andReturnUsing(function($post_id) {
                return $post_id === 123 ? (object) ['ID' => 123] : null;
            });

        $this->assertEquals(123, SanitizationHelper::validate_post_id(123));
        $this->assertFalse(SanitizationHelper::validate_post_id(0));
        $this->assertFalse(SanitizationHelper::validate_post_id(''));
        $this->assertFalse(SanitizationHelper::validate_post_id(-1));
    }

    /**
     * Test term ID validation.
     */
    public function test_validate_term_id(): void
    {
        $this->assertEquals(456, SanitizationHelper::validate_term_id(456));
        $this->assertFalse(SanitizationHelper::validate_term_id(0));
        $this->assertFalse(SanitizationHelper::validate_term_id(''));
        $this->assertEquals(1, SanitizationHelper::validate_term_id(-1));
    }

    /**
     * Test attachment ID validation.
     */
    public function test_validate_attachment_id(): void
    {
        \WP_Mock::userFunction('get_post')
            ->with(789)
            ->andReturn((object) ['ID' => 789, 'post_type' => 'attachment']);

        \WP_Mock::userFunction('wp_attachment_is_image')
            ->with(789)
            ->andReturn(true);

        $this->assertEquals(789, SanitizationHelper::validate_attachment_id(789));
        $this->assertFalse(SanitizationHelper::validate_attachment_id(0));
        $this->assertFalse(SanitizationHelper::validate_attachment_id(''));
    }

    /**
     * Test slug validation.
     */
    public function test_validate_slug(): void
    {
        \WP_Mock::userFunction('sanitize_title')
            ->with('Test Slug')
            ->andReturn('test-slug');

        $this->assertEquals('test-slug', SanitizationHelper::validate_slug('Test Slug'));

        \WP_Mock::userFunction('sanitize_title')
            ->with('')
            ->andReturn('');

        $this->assertEquals('default', SanitizationHelper::validate_slug('', 'default'));
    }

    /**
     * Test numeric range validation.
     */
    public function test_validate_numeric_range(): void
    {
        $this->assertEquals(5, SanitizationHelper::validate_numeric_range(5, 1, 10, 0));
        $this->assertEquals(0, SanitizationHelper::validate_numeric_range(15, 1, 10, 0));
        $this->assertEquals(5, SanitizationHelper::validate_numeric_range(-5, 1, 10, 0));
        $this->assertEquals(null, SanitizationHelper::validate_numeric_range(null, 1, 10, null));
    }

    /**
     * Test CSS rule building.
     */
    public function test_build_css_rule(): void
    {
        $rule = SanitizationHelper::build_css_rule('width', '100px');
        $this->assertStringContainsString('width:', $rule);
        $this->assertStringContainsString('100px', $rule);

        $this->assertEquals('', SanitizationHelper::build_css_rule('', '100px'));
        $this->assertEquals('', SanitizationHelper::build_css_rule('width', ''));
    }

    /**
     * Test CSS rule building prevents injection.
     */
    public function test_build_css_rule_prevents_injection(): void
    {
        $this->assertEquals('width: expressionalert1;', SanitizationHelper::build_css_rule('width', 'expression(alert(1))'));
        $this->assertEquals('widthmaliciouscode: 100px;', SanitizationHelper::build_css_rule('width; malicious: code', '100px'));
    }
}
