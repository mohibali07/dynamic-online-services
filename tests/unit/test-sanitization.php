<?php
/**
 * Unit Tests for Sanitization Class
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

use TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization;

/**
 * Test case for Sanitization helper class.
 *
 * @covers \TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization
 */
class SanitizationTest extends DYNOS_TestCase {

	/**
	 * Test CSS transform sanitization.
	 *
	 * @dataProvider provideTransformValues
	 */
	public function test_css_transform(string $input, string $expected): void {
		$result = Sanitization::css_transform($input);
		$this->assertSame($expected, $result);
	}

	/**
	 * Data provider for transform tests.
	 *
	 * @return array<array<string>>
	 */
	public function provideTransformValues(): array {
		return [
			'valid translate' => ['translate(10px, 20px)', 'translate(10px, 20px)'],
			'valid scale' => ['scale(1.5)', 'scale(1.5)'],
			'valid rotate' => ['rotate(45deg)', 'rotate(45deg)'],
			'multiple transforms' => ['translateX(10px) rotate(45deg) scale(2)', 'translateX(10px) rotate(45deg) scale(2)'],
			'invalid function' => ['hack()', ''],
			'script injection' => ['<script>alert(1)</script>', ''],
			'empty string' => ['', ''],
		];
	}

	/**
	 * Test CSS box-shadow sanitization.
	 *
	 * @dataProvider provideBoxShadowValues
	 */
	public function test_css_box_shadow(string $input, string $expected): void {
		$result = Sanitization::css_box_shadow($input);
		$this->assertSame($expected, $result);
	}

	/**
	 * Data provider for box-shadow tests.
	 *
	 * @return array<array<string>>
	 */
	public function provideBoxShadowValues(): array {
		return [
			'valid shadow' => ['0 4px 6px rgba(0,0,0,0.1)', '0 4px 6px rgba(0,0,0,0.1)'],
			'with color name' => ['2px 2px 4px red', '2px 2px 4px red'],
			'multiple shadows' => ['0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24)', '0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24)'],
			'invalid characters' => ['0 0 0 <script>', ''],
			'empty string' => ['', ''],
		];
	}

	/**
	 * Test CSS dimension sanitization.
	 *
	 * @dataProvider provideDimensionValues
	 */
	public function test_css_dimension(string $input, string $expected): void {
		$result = Sanitization::css_dimension($input);
		$this->assertSame($expected, $result);
	}

	/**
	 * Data provider for dimension tests.
	 *
	 * @return array<array<string>>
	 */
	public function provideDimensionValues(): array {
		return [
			'pixels' => ['100px', '100px'],
			'percentage' => ['50%', '50%'],
			'rem units' => ['2.5rem', '2.5rem'],
			'em units' => ['1.5em', '1.5em'],
			'viewport units' => ['100vh', '100vh'],
			'calc function' => ['calc(100% - 20px)', 'calc(100% - 20px)'],
			'invalid value' => ['<script>alert(1)</script>', ''],
			'just number' => ['100', '100'],
			'empty string' => ['', ''],
		];
	}

	/**
	 * Test post ID validation.
	 *
	 * @dataProvider providePostIds
	 */
	public function test_validate_post_id($input, $expected): void {
		// Mock WordPress function
		if ($expected !== false) {
			\WP_Mock::userFunction('get_post_status', [
				'args' => [$expected],
				'return' => 'publish',
				'times' => 1,
			]);
		}

		$result = Sanitization::validate_post_id($input);
		$this->assertSame($expected, $result);
	}

	/**
	 * Data provider for post ID tests.
	 *
	 * @return array<array<mixed>>
	 */
	public function providePostIds(): array {
		return [
			'valid integer' => [123, 123],
			'valid string integer' => ['456', 456],
			'zero' => [0, false],
			'negative' => [-1, false],
			'non-numeric string' => ['abc', false],
			'null' => [null, false],
			'float' => [12.5, 12],
		];
	}

	/**
	 * Test term ID validation with valid term.
	 */
	public function test_validate_term_id_valid(): void {
		$term_id = 123;
		$taxonomy = 'category';

		// Mock term_exists to return valid term
		\WP_Mock::userFunction('term_exists', [
			'args' => [$term_id, $taxonomy],
			'return' => $term_id,
			'times' => 1,
		]);

		$result = Sanitization::validate_term_id($term_id, $taxonomy);
		$this->assertSame($term_id, $result);
	}

	/**
	 * Test term ID validation with invalid term.
	 */
	public function test_validate_term_id_invalid(): void {
		$term_id = 999;
		$taxonomy = 'category';

		// Mock term_exists to return false
		\WP_Mock::userFunction('term_exists', [
			'args' => [$term_id, $taxonomy],
			'return' => false,
			'times' => 1,
		]);

		$result = Sanitization::validate_term_id($term_id, $taxonomy);
		$this->assertFalse($result);
	}

	/**
	 * Test slug sanitization.
	 *
	 * @dataProvider provideSlugValues
	 */
	public function test_sanitize_slug(string $input, string $expected): void {
		\WP_Mock::userFunction('sanitize_title', [
			'return' => function ($text) {
				return strtolower(str_replace([' ', '_'], '-', $text));
			},
		]);

		$result = Sanitization::sanitize_slug($input);
		$this->assertSame($expected, $result);
	}

	/**
	 * Data provider for slug tests.
	 *
	 * @return array<array<string>>
	 */
	public function provideSlugValues(): array {
		return [
			'simple text' => ['Hello World', 'hello-world'],
			'with underscores' => ['hello_world', 'hello-world'],
			'already slug' => ['my-slug', 'my-slug'],
			'special chars' => ['test@#$%', 'test@#$%'], // sanitize_title handles this
			'empty string' => ['', ''],
		];
	}

	/**
	 * Test escape CSS value for dangerous patterns.
	 */
	public function test_escape_css_value_dangerous_patterns(): void {
		$dangerous_inputs = [
			'expression(alert(1))',
			'javascript:alert(1)',
			'@import url(evil.css)',
			'url("javascript:alert(1)")',
			'<script>alert(1)</script>',
		];

		foreach ($dangerous_inputs as $input) {
			$result = Sanitization::escape_css_value($input);
			$this->assertSame('', $result, "Failed to block: $input");
		}
	}

	/**
	 * Test escape CSS value for safe patterns.
	 */
	public function test_escape_css_value_safe_patterns(): void {
		$safe_inputs = [
			'#ff0000',
			'rgba(255, 0, 0, 0.5)',
			'10px',
			'bold',
			'center',
		];

		foreach ($safe_inputs as $input) {
			$result = Sanitization::escape_css_value($input);
			$this->assertNotEmpty($result, "Incorrectly blocked safe value: $input");
		}
	}
}
