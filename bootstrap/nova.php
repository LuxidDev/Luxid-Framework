<?php

declare(strict_types=1);

/**
 * Registers every Nova component, page and layout.
 *
 * Shared by the PHP-FPM front controller and the worker entry point so both
 * see exactly the same component registry.
 *
 * @return void
 */

$root = dirname(__DIR__);

foreach (['components', 'pages', 'layouts', 'helpers'] as $directory) {
    $path = $root . '/nova/' . $directory;

    if (!is_dir($path)) {
        continue;
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($files as $file) {
        if ($file->isFile() && str_ends_with($file->getFilename(), '.php')) {
            require_once $file->getPathname();
        }
    }
}
