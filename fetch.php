<?php
$url = 'https://qbzvydkjvlutcsgdqikn.supabase.co/storage/v1/object/public/bloxpin-bucket/photos/thumbnails/_thumb.jpg';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP Code: $httpcode\n";
list($headers, $body) = explode("\r\n\r\n", $response, 2);
echo "Body:\n$body\n";
