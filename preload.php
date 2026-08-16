<?php

declare(strict_types=1);

/**
 * Opcache preload script.
 *
 * Compiles the framework and the application into shared memory once, when the
 * server starts, so no request pays to compile them. Enable it in php.ini:
 *
 *     opcache.preload=/path/to/your-app/preload.php
 *     opcache.preload_user=www-data
 *
 * Production only: preloaded files are frozen until PHP-FPM restarts, so a code
 * change will not be picked up until you reload the service.
 *
 * Pair it with:
 *
 *     opcache.enable=1
 *     opcache.memory_consumption=256
 *     opcache.max_accelerated_files=20000
 *     opcache.validate_timestamps=0
 */

require __DIR__ . '/vendor/autoload.php';

$result = (new Luxid\Foundation\Preloader(__DIR__))->load();

if (PHP_SAPI === 'cli') {
    printf("Luxid preloaded %d files (%d skipped).%s", $result['compiled'], $result['skipped'], PHP_EOL);
}
