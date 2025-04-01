<?php
function optimizeImage($sourcePath, $destinationPath, $quality = 80) {
    // Get image information
    $imageInfo = getimagesize($sourcePath);
    if ($imageInfo === false) {
        return false;
    }

    // Create image resource based on file type
    switch ($imageInfo[2]) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($sourcePath);
            break;
        default:
            return false;
    }

    // Save optimized image
    switch ($imageInfo[2]) {
        case IMAGETYPE_JPEG:
            imagejpeg($image, $destinationPath, $quality);
            break;
        case IMAGETYPE_PNG:
            imagepng($image, $destinationPath, 9);
            break;
    }

    imagedestroy($image);
    return true;
}

// Optimize hero background
$heroSource = 'images/hero-bg.jpg';
$heroOptimized = 'images/hero-bg-optimized.jpg';
if (file_exists($heroSource)) {
    optimizeImage($heroSource, $heroOptimized, 80);
    rename($heroOptimized, $heroSource);
}

// Optimize default property image
$propertySource = 'images/default-property.jpg';
$propertyOptimized = 'images/default-property-optimized.jpg';
if (file_exists($propertySource)) {
    optimizeImage($propertySource, $propertyOptimized, 80);
    rename($propertyOptimized, $propertySource);
}

echo "Image optimization complete!";
?> 