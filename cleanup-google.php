<?php

// Target directory to clean up
if (!isset($dir)) {
    $dir = __DIR__ . '/vendor/google/apiclient-services/src';
}

if (!is_dir($dir)) {
    // Check if we are running in local test environment where vendor is in parent or backup folder
    $dir = __DIR__ . '/democrm/Files/core/vendor/google/apiclient-services/src';
}

if (is_dir($dir)) {
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
} else {
    echo "Directory $dir not found. Skipping Google client cleanup.\n";
}

// --- Patch ViserLab License Check ---
$vendorDir = __DIR__ . '/vendor';
if (is_dir($vendorDir)) {
    echo "Vendor dir exists.\n";
    $laraminDir = $vendorDir . '/laramin';
    if (is_dir($laraminDir)) {
        echo "laramin folder exists: " . implode(', ', array_diff(scandir($laraminDir), ['.', '..'])) . "\n";
        $utilityDir = $laraminDir . '/utility';
        if (is_dir($utilityDir)) {
            echo "utility folder exists: " . implode(', ', array_diff(scandir($utilityDir), ['.', '..'])) . "\n";
            $srcDir = $utilityDir . '/src';
            if (is_dir($srcDir)) {
                echo "src folder exists: " . implode(', ', array_diff(scandir($srcDir), ['.', '..'])) . "\n";
                
                $onumotiFile = $srcDir . '/Onumoti.php';
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
                echo "Writing patched Onumoti.php...\n";
                file_put_contents($onumotiFile, $onumotiCode);
                
                $helpmateFile = $srcDir . '/Helpmate.php';
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
                echo "Writing patched Helpmate.php...\n";
                file_put_contents($helpmateFile, $helpmateCode);
            }
        }
    }
} else {
    echo "Vendor dir NOT found!\n";
}

// Local test environment patch
$localOnumoti = __DIR__ . '/democrm/Files/core/vendor/laramin/utility/src/Onumoti.php';
if (file_exists($localOnumoti)) {
    echo "Patching local environment Onumoti.php...\n";
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
    file_put_contents($localOnumoti, $onumotiCode);
}
$localHelpmate = __DIR__ . '/democrm/Files/core/vendor/laramin/utility/src/Helpmate.php';
if (file_exists($localHelpmate)) {
    echo "Patching local environment Helpmate.php...\n";
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
    file_put_contents($localHelpmate, $helpmateCode);
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
