<?php
// Generate a simple logo PNG
header('Content-Type: image/png');
$size = 200;
$img = imagecreate($size, $size);

$blue = imagecolorallocate($img, 10, 42, 94);
$white = imagecolorallocate($img, 255, 255, 255);
$red = imagecolorallocate($img, 215, 25, 33);

// Fill with blue
imagefill($img, 0, 0, $blue);

// Draw "P" in white
imagefilledrectangle($img, 60, 50, 140, 60, $white);
imagefilledrectangle($img, 60, 50, 70, 150, $white);
imagefilledrectangle($img, 60, 95, 120, 105, $white);
imagefilledrectangle($img, 110, 50, 120, 105, $white);

// Red accent at bottom
imagefilledrectangle($img, 0, $size - 8, $size, $size, $red);

imagepng($img);
imagedestroy($img);
