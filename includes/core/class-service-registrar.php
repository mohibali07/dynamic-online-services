<?php
/**
 * Service Registrar Class
 *
 * Handles registration of custom post types and taxonomies.
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Core;

if (!\defined('ABSPATH')) {
    exit;
}

use TechmireSolutions\DynamicOnlineServices\PostTypes\ServicePostType;
use TechmireSolutions\DynamicOnlineServices\Taxonomies\ServiceCategoryTaxonomy;
use TechmireSolutions\DynamicOnlineServices\Helpers\Options;

class ServiceRegistrar
{
    /**
     * Register post types and taxonomies.
     */
    public static function register(): void
    {
        // Retrieve settings (fallback to defaults if needed)
        $settings = Options::get();
        $service_slug  = $settings['service_post_type_slug'] ?? 'service';
        $taxonomy_slug = $settings['service_taxonomy_slug'] ?? 'service-category';

        // Register CPT
        $cpt = new ServicePostType($service_slug);
        $cpt->register();

        // Register taxonomy
        $tax = new ServiceCategoryTaxonomy($taxonomy_slug, [$service_slug]);
        $tax->register();
    }
}
?>
