<?php

// Target directory to clean up
if (!isset($dir)) {
    $dir = __DIR__ . '/vendor/google/apiclient-services/src';
}

if (!is_dir($dir)) {
    // Check if we are running in local test environment where vendor is in parent or backup folder
    $dir = __DIR__ . '/democrm/Files/core/vendor/google/apiclient-services/src';
}

if (!is_dir($dir)) {
    echo "Directory $dir not found. Skipping cleanup.\n";
    exit(0);
}

echo "Cleaning up Google API client services to reduce package size...\n";

$dirIterator = new DirectoryIterator($dir);
$deletedDirs = 0;
$deletedFiles = 0;

foreach ($dirIterator as $item) {
    if ($item->isDot()) {
        continue;
    }
    
    $path = $item->getPathname();
    if ($item->isDir()) {
        deleteDirectory($path);
        $deletedDirs++;
    } else if ($item->isFile() && $item->getExtension() === 'php') {
        unlink($path);
        $deletedFiles++;
    }
}

echo "Google API client services cleanup completed. Deleted $deletedDirs directories and $deletedFiles service files.\n";

function deleteDirectory($dirPath) {
    if (!is_dir($dirPath)) {
        return;
    }
    $files = array_diff(scandir($dirPath), array('.', '..'));
    foreach ($files as $file) {
        $filePath = $dirPath . '/' . $file;
        if (is_dir($filePath)) {
            deleteDirectory($filePath);
        } else {
            unlink($filePath);
        }
    }
    rmdir($dirPath);
}
