<?php

$base=$_REQUEST['image'];

echo $base;

// base64 encoded utf-8 string

$binary=base64_decode($base);

// binary, utf-8 bytes

header('Content-Type: bitmap; charset=utf-8');

// print($binary);

//$theFile = base64_decode($image_data);

$file = fopen('test.jpg', 'wb');

fwrite($file, $binary);

fclose($file);

echo '<img src=test.jpg>';

