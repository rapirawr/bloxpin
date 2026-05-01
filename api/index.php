<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure Laravel can write to /tmp
$compiledViews = '/tmp/storage/framework/views';
if (!is_dir($compiledViews)) {
    mkdir($compiledViews, 0777, true);
}

// Override Laravel Cache paths to /tmp dynamically
$_ENV['VIEW_COMPILED_PATH'] = $compiledViews;
$_ENV['APP_SERVICES_CACHE'] = '/tmp/bootstrap/cache/services.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/bootstrap/cache/packages.php';
$_ENV['APP_CONFIG_CACHE']   = '/tmp/bootstrap/cache/config.php';
$_ENV['APP_ROUTES_CACHE']   = '/tmp/bootstrap/cache/routes.php';
$_ENV['APP_EVENTS_CACHE']   = '/tmp/bootstrap/cache/events.php';

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<h1>Fatal Error Caught:</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
