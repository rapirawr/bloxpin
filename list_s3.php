<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $disk = \Illuminate\Support\Facades\Storage::disk('s3');
    $files = $disk->allFiles();
    echo "Files in bucket:\n";
    print_r($files);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
