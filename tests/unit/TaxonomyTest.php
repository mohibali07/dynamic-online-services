<?php
/**
 * Taxonomy Registration Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit;

use WP_Mock\Tools\TestCase;
use TechmireSolutions\DynamicOnlineServices\Taxonomies\ServiceCategoryTaxonomy;

/**
 * Test ServiceCategoryTaxonomy class.
 */
class TaxonomyTest extends TestCase
{
    /**
     * Set up test environment.
     */
    public function setUp(): void
    {
        parent::setUp();
        \WP_Mock::setUp();
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
     * Test taxonomy instantiation.
     */
    public function test_can_instantiate_taxonomy(): void
    {
        $taxonomy = new ServiceCategoryTaxonomy('service-category', ['services']);
        $this->assertInstanceOf(ServiceCategoryTaxonomy::class, $taxonomy);
    }

    /**
     * Test register method adds action hook.
     */
    public function test_register_adds_init_hook(): void
    {
        \WP_Mock::expectActionAdded('init', [\WP_Mock\Functions::type(ServiceCategoryTaxonomy::class), 'register_taxonomy']);

        $taxonomy = new ServiceCategoryTaxonomy('service-category', ['services']);
        $taxonomy->register();

        $this->assertConditionsMet();
    }

    /**
     * Test taxonomy registration calls WordPress function.
     */
    public function test_register_taxonomy_calls_wordpress_function(): void
    {
        \WP_Mock::userFunction('_x')
            ->andReturn('Service Categories');

        \WP_Mock::userFunction('__')
            ->andReturn('Categories');

        \WP_Mock::userFunction('register_taxonomy')
            ->once()
            ->with('service-category', ['services'], \WP_Mock\Functions::type('array'));

        $taxonomy = new ServiceCategoryTaxonomy('service-category', ['services']);
        $taxonomy->register_taxonomy();

        $this->assertConditionsMet();
    }

    /**
     * Test taxonomy accepts multiple post types.
     */
    public function test_taxonomy_accepts_multiple_post_types(): void
    {
        \WP_Mock::userFunction('_x')
            ->andReturn('Service Categories');

        \WP_Mock::userFunction('__')
            ->andReturn('Categories');

        \WP_Mock::userFunction('register_taxonomy')
            ->once()
            ->with('service-category', ['services', 'courses'], \WP_Mock\Functions::type('array'));

        $taxonomy = new ServiceCategoryTaxonomy('service-category', ['services', 'courses']);
        $taxonomy->register_taxonomy();

        $this->assertConditionsMet();
    }

    /**
     * Test taxonomy is hierarchical.
     */
    public function test_taxonomy_is_hierarchical(): void
    {
        \WP_Mock::userFunction('_x')
            ->andReturn('Service Categories');

        \WP_Mock::userFunction('__')
            ->andReturn('Categories');

        \WP_Mock::userFunction('register_taxonomy')
            ->once()
            ->with(
                'service-category',
                ['services'],
                 \Mockery::on(function ($args) {
                    return isset($args['hierarchical']) && $args['hierarchical'] === true;
                })
            );

        $taxonomy = new ServiceCategoryTaxonomy('service-category', ['services']);
        $taxonomy->register_taxonomy();

        $this->assertConditionsMet();
    }

    /**
     * Test taxonomy has REST API support.
     */
    public function test_taxonomy_has_rest_api_support(): void
    {
        \WP_Mock::userFunction('_x')
            ->andReturn('Service Categories');

        \WP_Mock::userFunction('__')
            ->andReturn('Categories');

        \WP_Mock::userFunction('register_taxonomy')
            ->once()
            ->with(
                'service-category',
                ['services'],
                 \Mockery::on(function ($args) {
                    return isset($args['show_in_rest']) && $args['show_in_rest'] === true;
                })
            );

        $taxonomy = new ServiceCategoryTaxonomy('service-category', ['services']);
        $taxonomy->register_taxonomy();

        $this->assertConditionsMet();
    }
}
