<?php
declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Tests\Unit\Helpers;

if ( ! defined( "ABSPATH" ) ) {
	exit;
}
/**
 * Fonts Helper Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

use PHPUnit\Framework\TestCase;
use TechmireSolutions\DynamicOnlineServices\Helpers\Fonts;

/**
 * Test the Fonts helper class.
 */
class FontsTest extends TestCase
{


	/**
	 * Test is_google_font returns false for standard fonts.
	 */
	public function test_is_google_font_returns_false_for_standard_fonts(): void
	{
		$this->assertFalse(Fonts::is_google_font('sans-serif'));
		$this->assertFalse(Fonts::is_google_font('serif'));
		$this->assertFalse(Fonts::is_google_font('monospace'));
		$this->assertFalse(Fonts::is_google_font('Helvetica'));
	}

	/**
	 * Test is_google_font returns true for custom fonts.
	 */
	public function test_is_google_font_returns_true_for_custom_fonts(): void
	{
		$this->assertTrue(Fonts::is_google_font('Roboto'));
		$this->assertTrue(Fonts::is_google_font('Open Sans'));
		$this->assertTrue(Fonts::is_google_font('Lato'));
	}

	/**
	 * Test is_google_font handles empty input.
	 */
	public function test_is_google_font_handles_empty_input(): void
	{
		$this->assertFalse(Fonts::is_google_font(''));
		$this->assertFalse(Fonts::is_google_font(null));
	}

	/**
	 * Test encode_google_font encodes spaces correctly.
	 */
	public function test_encode_google_font_encodes_spaces(): void
	{
		\WP_Mock::userFunction('sanitize_text_field', [
            'return' => function($str) { return $str; }
        ]);
		$encoded = Fonts::encode_google_font('Open Sans');
		$this->assertStringContainsString('Open', $encoded);
		$this->assertStringContainsString('Sans', $encoded);
		// Should not contain raw spaces
		$this->assertStringNotContainsString(' ', $encoded);
	}

	/**
	 * Test encode_google_font returns empty for invalid input.
	 */
	public function test_encode_google_font_returns_empty_for_invalid_input(): void
	{
		$this->assertSame('', Fonts::encode_google_font(''));
		$this->assertSame('', Fonts::encode_google_font(null));
	}

	/**
	 * Test encode_google_font sanitizes input.
	 */
	public function test_encode_google_font_sanitizes_input(): void
	{
		$encoded = Fonts::encode_google_font('<script>alert("xss")</script>');
		// Should not contain HTML tags
		$this->assertStringNotContainsString('<script>', $encoded);
		$this->assertStringNotContainsString('</script>', $encoded);
	}

	/**
	 * Test get_standard_fonts returns array.
	 */
	public function test_get_standard_fonts_returns_array(): void
	{
		$fonts = Fonts::get_standard_fonts();
		$this->assertIsArray($fonts);
		$this->assertContains('sans-serif', $fonts);
		$this->assertContains('serif', $fonts);
	}
}
