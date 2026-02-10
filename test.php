<?php

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.azzahracomputertegal.com/api/upload-image',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => [
        'filename' => 'testcurl.jpg',
        'type' => 'produk',
        'image' => curl_file_create(__DIR__ . '/test.jpg') // buat file test.jpg di folder yang sama
    ],
]);

$result = curl_exec($curl);
$error = curl_error($curl);
curl_close($curl);

echo "<pre>";
echo "RESULT:\n";
var_dump($result);

echo "\n\nERROR:\n";
var_dump($error);
echo "</pre>";
