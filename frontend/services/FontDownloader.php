<?php
/**
 * Font Downloader Service
 *
 * Handles downloading Google Fonts for local hosting.
 *
 * @package Dynamic_Online_Services
 * @subpackage Services
 */

// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_error_log -- This file intentionally uses error_log for font download failure diagnostics

declare(strict_types=1);

namespace TechmireSolutions\DynamicOnlineServices\Services;

if (!defined('ABSPATH')) {
	exit;
}

/**
 * FontDownloader class.
 *
 * Downloads Google Fonts and stores them locally for GDPR compliance.
 */
class FontDownloader
{


	/**
	 * WordPress uploads directory path.
	 *
	 * @var string
	 */
	private $uploads_dir;

	/**
	 * Fonts directory path.
	 *
	 * @var string
	 */
	private $fonts_dir;

	/**
	 * Fonts directory URL.
	 *
	 * @var string
	 */
	private $fonts_url;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$upload_dir = wp_upload_dir();
		$this->uploads_dir = $upload_dir['basedir'];
		$this->fonts_dir = $this->uploads_dir . '/dynos-fonts';
		$this->fonts_url = $upload_dir['baseurl'] . '/dynos-fonts';
	}

	/**
	 * Download a font family locally.
	 *
	 * @since 1.1.0
	 * @param string $font_family Font family name.
	 * @return bool True on success, false on failure.
	 */
	public function download_font_family(string $font_family): bool
	{
		if (empty($font_family)) {
			return false;
		}

		// Create directory if needed
		if (!$this->create_font_directory()) {
			return false;
		}

		// Fetch Google Font CSS
		$css_content = $this->fetch_google_font_css($font_family);
		if (empty($css_content)) {
			return false;
		}

		// Parse font URLs from CSS
		$font_urls = $this->parse_font_urls($css_content);
		if (empty($font_urls)) {
			return false;
		}

		// Download font files
		$downloaded_files = array();
		foreach ($font_urls as $url) {
			$local_path = $this->download_font_file($url, $font_family);
			if ($local_path) {
				$downloaded_files[$url] = $local_path;
			}
		}

		if (empty($downloaded_files)) {
			return false;
		}

		// Generate local CSS
		return $this->generate_local_css($font_family, $css_content, $downloaded_files);
	}

	/**
	 * Create font directory if it doesn't exist.
	 *
	 * @since 1.1.0
	 * @return bool True on success, false on failure.
	 */
	private function create_font_directory(): bool
	{
		if (file_exists($this->fonts_dir) && is_dir($this->fonts_dir)) {
			return true;
		}

		if (!wp_mkdir_p($this->fonts_dir)) {
			error_log('Dynamic Online Services: Failed to create fonts directory: ' . $this->fonts_dir);
			return false;
		}

		// Create .htaccess for security
		$htaccess_content = "# Protect fonts directory\n<FilesMatch \"\\.(woff2?|ttf|eot)$\">\n\tHeader set Access-Control-Allow-Origin \"*\"\n</FilesMatch>\n";
		global $wp_filesystem;
		if (empty($wp_filesystem)) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}
		$wp_filesystem->put_contents($this->fonts_dir . '/.htaccess', $htaccess_content, FS_CHMOD_FILE);

		return true;
	}

	/**
	 * Fetch Google Font CSS from API.
	 *
	 * @since 1.1.0
	 * @param string $font_family Font family name.
	 * @return string CSS content or empty string on failure.
	 */
	private function fetch_google_font_css(string $font_family): string
	{
		$encoded_family = str_replace(' ', '+', sanitize_text_field($font_family));
		$url = 'https://fonts.googleapis.com/css2?family=' . rawurlencode($encoded_family) . ':wght@400;700&display=swap';

		// Use WordPress HTTP API with WOFF2 user agent for modern fonts
		$response = wp_remote_get(
			$url,
			array(
				'timeout'    => 30,
				'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
			)
		);

		if (is_wp_error($response)) {
			error_log('Dynamic Online Services: Failed to fetch Google Font CSS for ' . $font_family . ': ' . $response->get_error_message());
			return '';
		}

		$body = wp_remote_retrieve_body($response);
		return !empty($body) ? $body : '';
	}

	/**
	 * Parse font file URLs from CSS content.
	 *
	 * @since 1.1.0
	 * @param string $css CSS content.
	 * @return array Array of font file URLs.
	 */
	private function parse_font_urls(string $css): array
	{
		$urls = array();

		// Match URLs in url() declarations
		if (preg_match_all('/url\((https:\/\/[^)]+\.woff2?)\)/', $css, $matches)) {
			$urls = array_unique($matches[1]);
		}

		return $urls;
	}

	/**
	 * Download a single font file.
	 *
	 * @since 1.1.0
	 * @param string $url        Font file URL.
	 * @param string $font_family Font family name for subdirectory.
	 * @return string|false Local file path on success, false on failure.
	 */
	private function download_font_file(string $url, string $font_family)
	{
		// Create font-specific subdirectory
		$font_slug = sanitize_file_name(strtolower(str_replace(' ', '-', $font_family)));
		$font_subdir = $this->fonts_dir . '/' . $font_slug;

		if (!file_exists($font_subdir)) {
			if (!wp_mkdir_p($font_subdir)) {
				error_log('Dynamic Online Services: Failed to create font subdirectory: ' . $font_subdir);
				return false;
			}
		}

		// Generate filename from URL
		$filename = basename(wp_parse_url($url, PHP_URL_PATH));
		if (empty($filename)) {
			error_log('Dynamic Online Services: Invalid font URL (no filename): ' . $url);
			return false;
		}

		$local_path = $font_subdir . '/' . $filename;

		// Skip if already downloaded
		if (file_exists($local_path)) {
			return $local_path;
		}

		// FIX: Check available disk space before downloading
		$free_space = disk_free_space($font_subdir);
		if (false !== $free_space && $free_space < (5 * 1024 * 1024)) { // Require at least 5MB free
			error_log('Dynamic Online Services: Insufficient disk space for font download. Free: ' . $free_space);
			return false;
		}

		// Download file
		$response = wp_remote_get(
			$url,
			array(
				'timeout' => 30,
			)
		);

		if (is_wp_error($response)) {
			error_log('Dynamic Online Services: Failed to download font file ' . $url . ': ' . $response->get_error_message());
			return false;
		}

		$font_data = wp_remote_retrieve_body($response);
		if (empty($font_data)) {
			error_log('Dynamic Online Services: Empty response body for font URL: ' . $url);
			return false;
		}

		// FIX: Validate file size (prevent downloading massive files)
		$data_size = strlen($font_data);
		$max_font_size = 2 * 1024 * 1024; // 2MB limit for a single font file
		if ($data_size > $max_font_size) {
			error_log('Dynamic Online Services: Font file too large: ' . $data_size . ' bytes (max: ' . $max_font_size . ')');
			return false;
		}

		// FIX: Validate MIME type to prevent malware/malicious files
		if (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$detected_mime = finfo_buffer($finfo, $font_data);
			finfo_close($finfo);

			// Allow font MIME types and generic application types (Google Fonts may return these)
			$allowed_mimes = [
				'font/woff2',
				'font/woff',
				'application/font-woff2',
				'application/font-woff',
				'application/octet-stream', // Google Fonts sometimes uses this
				'binary/octet-stream',
			];

			if (!in_array($detected_mime, $allowed_mimes, true)) {
				error_log('Dynamic Online Services: Invalid font MIME type: ' . $detected_mime . ' for URL: ' . $url);
				return false;
			}
		}

		// Save file with error handling using WP_Filesystem
		global $wp_filesystem;
		if (empty($wp_filesystem)) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}
		$saved = $wp_filesystem->put_contents($local_path, $font_data, FS_CHMOD_FILE);
		if (false === $saved) {
			error_log('Dynamic Online Services: Failed to save font file: ' . $local_path . ' (check permissions)');
			return false;
		}

		return $local_path;
	}

	/**
	 * Generate local CSS file with updated paths.
	 *
	 * @since 1.1.0
	 * @param string $font_family      Font family name.
	 * @param string $original_css     Original CSS from Google.
	 * @param array  $downloaded_files Map of URLs to local paths.
	 * @return bool True on success, false on failure.
	 */
	private function generate_local_css(string $font_family, string $original_css, array $downloaded_files): bool
	{
		$font_slug = sanitize_file_name(strtolower(str_replace(' ', '-', $font_family)));
		$css_file = $this->fonts_dir . '/' . $font_slug . '/font.css';

		// Replace remote URLs with local URLs
		$local_css = $original_css;
		foreach ($downloaded_files as $remote_url => $local_path) {
			// Convert local path to URL
			$local_url = str_replace($this->fonts_dir, $this->fonts_url, $local_path);
			$local_css = str_replace($remote_url, $local_url, $local_css);
		}

		// Save CSS file using WP_Filesystem
		global $wp_filesystem;
		if (empty($wp_filesystem)) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}
		$saved = $wp_filesystem->put_contents($css_file, $local_css, FS_CHMOD_FILE);
		if (false === $saved) {
			error_log('Dynamic Online Services: Failed to save CSS file: ' . $css_file);
			return false;
		}

		return true;
	}

	/**
	 * Get local CSS URL for a font family.
	 *
	 * @since 1.1.0
	 * @param string $font_family Font family name.
	 * @return string|false CSS file URL or false if not found.
	 */
	public function get_local_css_url(string $font_family)
	{
		$font_slug = sanitize_file_name(strtolower(str_replace(' ', '-', $font_family)));
		$css_file = $this->fonts_dir . '/' . $font_slug . '/font.css';

		if (!file_exists($css_file)) {
			return false;
		}

		return $this->fonts_url . '/' . $font_slug . '/font.css';
	}

	/**
	 * Check if a font family is available locally.
	 *
	 * @since 1.1.0
	 * @param string $font_family Font family name.
	 * @return bool True if available locally, false otherwise.
	 */
	public function is_font_local(string $font_family): bool
	{
		return $this->get_local_css_url($font_family) !== false;
	}
}
