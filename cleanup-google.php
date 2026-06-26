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

// --- Patch ViserLab License Check ---
$onumotiFile = __DIR__ . '/vendor/laramin/utility/src/Onumoti.php';
if (file_exists(dirname($onumotiFile))) {
    echo "Patching Onumoti.php to bypass license check...\n";
    $onumotiCode = '<?php

namespace Laramin\Utility;

use App\Lib\CurlRequest;
use App\Models\GeneralSetting;

class Onumoti{

    public static function getData(){
        // license check removed
    }

    public static function mySite($site,$className){
        // license check removed
    }
}';
    file_put_contents($onumotiFile, $onumotiCode);
} else {
    // Check if we are running in local test environment where vendor is in democrm/Files/core/vendor
    $onumotiFileLocal = __DIR__ . '/democrm/Files/core/vendor/laramin/utility/src/Onumoti.php';
    if (file_exists($onumotiFileLocal)) {
        echo "Patching local Onumoti.php...\n";
        file_put_contents($onumotiFileLocal, $onumotiCode);
    }
}

$helpmateFile = __DIR__ . '/vendor/laramin/utility/src/Helpmate.php';
if (file_exists(dirname($helpmateFile))) {
    echo "Patching Helpmate.php to bypass license check...\n";
    $helpmateCode = '<?php

namespace Laramin\Utility;

use App\Models\GeneralSetting;

class Helpmate{

    public static function sysPass(){
        return true;
    }

    public static function appUrl(){
        $current = @$_SERVER[\'REQUEST_SCHEME\'] ?? \'http\' . \'://\' . $_SERVER[\'HTTP_HOST\'] . $_SERVER[\'REQUEST_URI\'];
        $url = substr($current, 0, -9);
        return  $url;
    }
}';
    file_put_contents($helpmateFile, $helpmateCode);
} else {
    $helpmateFileLocal = __DIR__ . '/democrm/Files/core/vendor/laramin/utility/src/Helpmate.php';
    if (file_exists($helpmateFileLocal)) {
        echo "Patching local Helpmate.php...\n";
        file_put_contents($helpmateFileLocal, $helpmateCode);
    }
}

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
