<?php
declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Helpers;

if ( ! defined( "ABSPATH" ) ) {
	exit;
}
/**
 * StyleBuilder Helper Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

use WP_Mock\Tools\TestCase;
use TechmireSolutions\DynamicOnlineServices\Helpers\StyleBuilder;
use TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization;

/**
 * Test StyleBuilder helper class.
 */
class StyleBuilderTest extends TestCase
{
	/**
	 * Set up test environment.
	 */
	public function setUp(): void
	{
		parent::setUp();
		\WP_Mock::setUp();

		// Load dependencies
		if (!class_exists('TechmireSolutions\DynamicOnlineServices\Helpers\Sanitization')) {
			require_once dirname(__DIR__, 3) . '/includes/helpers/sanitization.php';
		}
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
	 * Test build hero inner style with height only.
	 */
	public function test_build_hero_inner_style_with_height(): void
	{
		$height = '500px';

		$result = StyleBuilder::build_hero_inner_style($height);

		$this->assertStringContainsString('height:', $result);
		$this->assertStringContainsString('500px', $result);
	}

	/**
	 * Test build hero inner style with image URL.
	 */
	public function test_build_hero_inner_style_with_image(): void
	{
		$height = '500px';
		$image_url = 'https://example.com/image.jpg';

		\WP_Mock::userFunction('esc_url')
			->once()
			->with($image_url)
			->andReturn($image_url);

		$result = StyleBuilder::build_hero_inner_style($height, $image_url);

		$this->assertStringContainsString('height:', $result);
		$this->assertStringContainsString('background-image:', $result);
		$this->assertStringContainsString($image_url, $result);
	}

	/**
	 * Test build hero inner style with empty values.
	 */
	public function test_build_hero_inner_style_empty_values(): void
	{
		$result = StyleBuilder::build_hero_inner_style('', '');

		$this->assertEquals('', $result);
	}

	/**
	 * Test build hero content style with color and font.
	 */
	public function test_build_hero_content_style_complete(): void
	{
		$color = '#ffffff';
		$font = 'Roboto';

		\WP_Mock::userFunction('sanitize_hex_color')
			->once()
			->with($color)
			->andReturn($color);

		\WP_Mock::userFunction('esc_attr')
			->once()
			->with($font)
			->andReturn($font);

		$result = StyleBuilder::build_hero_content_style($color, $font);

		$this->assertStringContainsString('color:', $result);
		$this->assertStringContainsString($color, $result);
		$this->assertStringContainsString('font-family:', $result);
		$this->assertStringContainsString($font, $result);
	}

	/**
	 * Test build hero content style with invalid color.
	 */
	public function test_build_hero_content_style_invalid_color(): void
	{
		$color = 'invalid-color';
		$font = 'Roboto';

		\WP_Mock::userFunction('sanitize_hex_color')
			->once()
			->with($color)
			->andReturn(''); // Invalid color returns empty

		\WP_Mock::userFunction('esc_attr')
			->once()
			->with($font)
			->andReturn($font);

		$result = StyleBuilder::build_hero_content_style($color, $font);

		$this->assertStringNotContainsString('color:', $result);
		$this->assertStringContainsString('font-family:', $result);
	}

	/**
	 * Test build grid style with all arguments.
	 */
	public function test_build_grid_style_complete(): void
	{
		$args = [
			'grid_style' => 'grid-template-columns: repeat(3, 1fr)',
			'column_gap' => '20px',
			'row_gap' => '30px',
		];

		\WP_Mock::userFunction('wp_parse_args')
			->once()
			->andReturnUsing(function ($args, $defaults) {
				return array_merge($defaults, $args);
			});

		$result = StyleBuilder::build_grid_style($args);

		$this->assertStringContainsString('grid-template-columns', $result);
		$this->assertStringContainsString('column-gap:', $result);
		$this->assertStringContainsString('20px', $result);
		$this->assertStringContainsString('row-gap:', $result);
		$this->assertStringContainsString('30px', $result);
	}

	/**
	 * Test build grid style with defaults.
	 */
	public function test_build_grid_style_with_defaults(): void
	{
		\WP_Mock::userFunction('wp_parse_args')
			->once()
			->andReturnUsing(function ($args, $defaults) {
				return $defaults;
			});

		$result = StyleBuilder::build_grid_style([]);

		$this->assertEquals('', $result);
	}

	/**
	 * Test build grid style with only grid template.
	 */
	public function test_build_grid_style_grid_only(): void
	{
		$args = [
			'grid_style' => 'grid-template-columns: repeat(4, 1fr)',
		];

		\WP_Mock::userFunction('wp_parse_args')
			->once()
			->andReturnUsing(function ($args, $defaults) {
				return array_merge($defaults, $args);
			});

		$result = StyleBuilder::build_grid_style($args);

		$this->assertStringContainsString('grid-template-columns', $result);
		$this->assertStringNotContainsString('column-gap', $result);
		$this->assertStringNotContainsString('row-gap', $result);
	}
}
