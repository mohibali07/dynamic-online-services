<?php
/**
 * Autoloader
 *
 * @package Dynamic_Online_Services
 */

namespace DynamicOnlineServices;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Autoloader class.
 */
class Autoloader
{

    /**
     * Run autoloader.
     *
     * @return void
     */
    public static function run(): void
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Autoload.
     *
     * @param string $class_name Class name.
     * @return void
     */
    public static function autoload($class_name): void
    {
        if (strpos($class_name, 'DynamicOnlineServices\\') !== 0) {
            return;
        }

        // Remove namespace from class name.
        $relative_class = substr($class_name, strlen('DynamicOnlineServices\\'));

        // Map namespace to includes directory.
        // We will map 'DynamicOnlineServices' to 'includes'.
        // Sub-namespaces will map to sub-directories.
        // e.g. DynamicOnlineServices\Core\Plugin -> includes/core/class-plugin.php or Plugin.php
        // Let's stick to PSR-4 strict for now: includes/Core/Plugin.php
        // But existing folders are lowercase.

        $file_parts = explode('\\', $relative_class);

        // Handle lowercasing for directories to match existing structure if we want to keep it,
        // or we can just rename directories.
        // For now, let's try to map strictly but handle case if needed.
        // Actually, let's just map to the file path.

        $file_path = DOC_PLUGIN_DIR . 'includes/';

        // Loop through parts
        $last_index = count($file_parts) - 1;
        foreach ($file_parts as $index => $part) {
            if ($index === $last_index) {
                // Filename
                $file_path .= $part . '.php';
            } else {
                // Directory - convert to lowercase/hyphenated if needed?
                // Let's assume we will rename directories to PascalCase or match namespace.
                // OR we convert namespace to lowercase for directory.
                $file_path .= strtolower(str_replace('_', '-', $part)) . '/';
            }
        }

        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }
}
