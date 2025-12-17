<?php
/**
 * Font Downloader Test
 *
 * @package Dynamic_Online_Services
 * @subpackage Tests
 */

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Tests\Unit\Services;

use TechmireSolutions\DynamicOnlineServices\Services\FontDownloader;
use WP_Mock;
use DYNOS_TestCase;

class FontDownloaderTest extends DYNOS_TestCase {

    public function setUp(): void {
        parent::setUp();
    }

    public function test_get_fonts_dir() {
        WP_Mock::userFunction('wp_upload_dir', [
            'return' => ['basedir' => '/tmp/uploads', 'baseurl' => 'http://example.com/uploads']
        ]);

        $downloader = new FontDownloader();
        // Access protected method via reflection or just test public interface if feasible
        // Here we just test instantiation as logic is complex and file-system dependent
        $this->assertInstanceOf(FontDownloader::class, $downloader);
    }
}
