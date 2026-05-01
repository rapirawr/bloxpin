<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $disk = \Illuminate\Support\Facades\Storage::disk('s3');
    $filename = 'test_final_' . time() . '.txt';
    $disk->put($filename, 'test content');
    
    echo "1. File berhasil di-upload: $filename\n";
    
    $url = $disk->url($filename);
    echo "2. URL dari Laravel: $url\n";
    
    // Test fetch the URL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "3. HTTP Response Code: $httpcode\n";
    if ($httpcode !== 200) {
        echo "4. Error Body: $response\n";
    } else {
        echo "4. Content: $response\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
