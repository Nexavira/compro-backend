<?php

function cleanBladeComments($file) {
    $content = file_get_contents($file);
    // Remove HTML comments <!-- ... -->
    $content = preg_replace('/<!--.*?-->/s', '', $content);
    // Remove Blade comments {{-- ... --}}
    $content = preg_replace('/\{\{--.*?--\}\}/s', '', $content);
    // Remove empty lines
    $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);
    file_put_contents($file, $content);
    echo "Cleaned Blade: $file\n";
}

function cleanPhpComments($file) {
    $content = file_get_contents($file);
    $tokens = token_get_all($content);
    $output = '';
    foreach ($tokens as $token) {
        if (is_array($token)) {
            if ($token[0] === T_COMMENT || $token[0] === T_DOC_COMMENT) {
                continue;
            }
            $output .= $token[1];
        } else {
            $output .= $token;
        }
    }
    $output = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n\n", $output);
    file_put_contents($file, $output);
    echo "Cleaned PHP: $file\n";
}

$bladeFiles = glob('D:\Kerja\Nexavira\Project\compro-backend\resources\views\filament\widgets\*.blade.php');
foreach ($bladeFiles as $f) {
    cleanBladeComments($f);
}

$phpFiles = glob('D:\Kerja\Nexavira\Project\compro-backend\app\Filament\Widgets\*.php');
foreach ($phpFiles as $f) {
    cleanPhpComments($f);
}

echo "Done.";
