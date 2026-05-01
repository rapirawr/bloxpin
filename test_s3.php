<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $disk = \Illuminate\Support\Facades\Storage::disk('s3');
    $disk->put('test.txt', 'hello world');
    echo "Upload to S3 successful.\n";
    echo "URL: " . $disk->url('test.txt') . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
