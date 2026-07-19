<?php

function cleanBladeComments($file) {
    $content = file_get_contents($file);
    // Remove HTML comments <!-- ... -->
    $content = preg_replace('/<!--.*?-->/s', '', $content);
    // Remove Blade comments {{-- ... --}}
    $content = preg_replace('/\{\{--.*?--\}\}/s', '', $content);
    // Remove multiple empty lines
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
    // Remove multiple empty lines
    $output = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n\n", $output);
    file_put_contents($file, $output);
    echo "Cleaned PHP: $file\n";
}

function scanDirRecursive($dir, $extensions) {
    $results = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            foreach ($extensions as $ext) {
                if (str_ends_with($file->getFilename(), $ext)) {
                    $results[] = $file->getPathname();
                }
            }
        }
    }
    return $results;
}

$directories = ['app', 'resources/views', 'routes', 'database', 'config'];

foreach ($directories as $dir) {
    if (!is_dir($dir)) continue;

    $phpFiles = scanDirRecursive($dir, ['.php']);
    foreach ($phpFiles as $file) {
        if (str_ends_with($file, '.blade.php')) {
            cleanBladeComments($file);
        } else {
            cleanPhpComments($file);
        }
    }
}

echo "Global cleanup done.\n";
