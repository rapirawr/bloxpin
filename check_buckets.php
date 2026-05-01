<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Aws\S3\S3Client;

$key = env('AWS_ACCESS_KEY_ID');
$secret = env('AWS_SECRET_ACCESS_KEY');
$region = env('AWS_DEFAULT_REGION');
$endpoint = env('AWS_ENDPOINT');

try {
    $s3 = new S3Client([
        'version' => 'latest',
        'region'  => $region,
        'endpoint' => $endpoint,
        'use_path_style_endpoint' => true,
        'credentials' => [
            'key'    => $key,
            'secret' => $secret,
        ],
    ]);

    $result = $s3->listBuckets();
    echo "Daftar Bucket yang Anda miliki:\n";
    foreach ($result['Buckets'] as $bucket) {
        echo "- " . $bucket['Name'] . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
