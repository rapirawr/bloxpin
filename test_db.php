<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$photos = \App\Models\Photo::latest()->take(3)->get();
foreach ($photos as $photo) {
    echo "Photo ID: " . $photo->id . "\n";
    echo "Title: " . $photo->title . "\n";
    echo "image_path in DB: " . $photo->getRawOriginal('image_path') . "\n";
    echo "thumbnail_path in DB: " . $photo->getRawOriginal('thumbnail_path') . "\n";
    echo "getImageUrlAttribute: " . $photo->image_url . "\n";
    echo "getThumbnailUrlAttribute: " . $photo->thumbnail_url . "\n\n";
}
