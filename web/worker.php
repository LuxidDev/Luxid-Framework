<?php

declare(strict_types=1);

/**
 * FrankenPHP worker entry point.
 *
 * Boots the application once and serves many requests from the same process,
 * so no request pays for autoloading, provider discovery, route registration or
 * connecting to the database.
 *
 * Run it with:
 *
 *     FRANKENPHP_CONFIG="worker ./web/worker.php" frankenphp php-server -r web/
 *
 * or in a Caddyfile:
 *
 *     php_server {
 *         worker ./web/worker.php
 *     }
 *
 * Under plain PHP-FPM this file is unused; web/index.php serves those requests.
 */

use Luxid\FrankenPHP\Adapter;

require __DIR__ . '/../vendor/autoload.php';

$root = dirname(__DIR__);

Dotenv\Dotenv::createImmutable($root)->safeLoad();

/** @var array<string, mixed> $config */
$config = require $root . '/config/config.php';

// Register Nova components once, for the life of the worker.
require $root . '/bootstrap/nova.php';

Adapter::run($root, $config, [
    // Retire the worker periodically so a slow leak cannot run forever.
    'max_requests' => (int) ($_ENV['LUXID_WORKER_MAX_REQUESTS'] ?? 1000),
    'memory_limit' => (int) ($_ENV['LUXID_WORKER_MEMORY_LIMIT'] ?? 256 * 1024 * 1024),
]);
