<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "Loading bootstrap...\n";
require_once __DIR__ . '/bootstrap.php';
echo "Bootstrap loaded.\n";

echo "Checking Sanitization class...\n";
if (class_exists('\\DynamicOnlineServices\\Helpers\\Sanitization')) {
    echo "Sanitization class exists.\n";
} else {
    echo "Sanitization class NOT found.\n";
}
