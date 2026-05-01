<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create a dummy image
$image = imagecreatetruecolor(400, 300);
$bg = imagecolorallocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
imagefill($image, 0, 0, $bg);
$text_color = imagecolorallocate($image, 255, 255, 255);
imagestring($image, 5, 100, 150, "SUPABASE S3 TEST", $text_color);

$tempPath = tempnam(sys_get_temp_dir(), 'test_img_');
imagejpeg($image, $tempPath);
imagedestroy($image);

// Fake an upload
$file = new \Illuminate\Http\UploadedFile($tempPath, 'test_supabase.jpg', 'image/jpeg', null, true);

$service = app(\App\Services\PhotoService::class);
$user = \App\Models\User::first();

try {
    $photo = $service->upload($user, $file, 'Test S3 Upload Success!');
    echo "Photo successfully created in DB: " . $photo->id . "\n";
    echo "URL: " . $photo->thumbnail_url . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
unlink($tempPath);
