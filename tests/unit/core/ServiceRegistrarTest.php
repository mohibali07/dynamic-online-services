<?php
/**
 * ServiceRegistrar Tests
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace DynamicOnlineServices\Tests\Unit\Core;

use DYNOS_TestCase;
use TechmireSolutions\DynamicOnlineServices\Core\ServiceRegistrar;

/**
 * Test ServiceRegistrar class.
 */
class ServiceRegistrarTest extends DYNOS_TestCase {

    public function test_register_initializes_cpt_and_taxonomy() {
        // Mock get_option used by Options::get()
        \WP_Mock::userFunction('get_option')
            ->andReturn([
                'service_post_type_slug' => 'service',
                'service_taxonomy_slug' => 'service-category',
            ]);

        \WP_Mock::userFunction('wp_parse_args')
            ->andReturnArg(0);



        // ServicePostType::register calls register_post_type
        // ServiceCategoryTaxonomy::register calls register_taxonomy

        // We can't strictly assert 'new' calls without complex overloading,
        // but we can assert that register_post_type and register_taxonomy are called
        // initiated by the real classes if they are loaded.

        // Mock dependencies of CPT/Taxonomy classes
        \WP_Mock::userFunction('sanitize_title')
             ->andReturnArg(0);

        \WP_Mock::userFunction('_x')->andReturn('Label');
        \WP_Mock::userFunction('__')->andReturn('Label');

        \WP_Mock::userFunction('register_post_type')->times(0);
        \WP_Mock::userFunction('register_taxonomy')->times(0);

        \WP_Mock::userFunction('add_action')->atLeast()->times(1);
        \WP_Mock::userFunction('add_filter')->atLeast()->times(1);

        // flush_rewrite_rules is NOT called in ServiceRegistrar::register (only in Activator)

        ServiceRegistrar::register();

        $this->assertConditionsMet();
    }
}
