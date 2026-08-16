<?php

declare(strict_types=1);

/**
 * Luxid front controller.
 *
 * Boots the application, registers Nova components and route tables, then hands
 * the request to the kernel. `run()` flushes the status code, headers and body,
 * so nothing here needs to echo.
 */

use Luxid\Foundation\Application;
use Luxid\Nova\ActionDispatcher;
use Luxid\Nova\Compiler;

require_once __DIR__ . '/../vendor/autoload.php';

$root = dirname(__DIR__);

Dotenv\Dotenv::createImmutable($root)->safeLoad();

/** @var array<string, mixed> $config */
$config = require $root . '/config/config.php';

if (class_exists(Compiler::class)) {
    $novaConfig = $root . '/nova/nova.json';

    if (is_file($novaConfig)) {
        $novaSettings = json_decode((string) file_get_contents($novaConfig), true) ?: [];

        if ($novaSettings['compiler']['cache']['enabled'] ?? false) {
            // Relative cache paths are resolved against the project root so the
            // compiler does not depend on the process working directory.
            $cachePath = $novaSettings['compiler']['cache']['path'] ?? 'storage/framework/nova';
            Compiler::setCachePath(str_starts_with($cachePath, '/') ? $cachePath : $root . '/' . $cachePath);
            Compiler::enableDebug($novaSettings['compiler']['cache']['debug'] ?? false);
        }
    }
}

// Register every component, page and layout. Shared with web/worker.php so
// both entry points see the same registry.
require $root . '/bootstrap/nova.php';

$app = new Application($root, $config);

require_once $root . '/routes/api.php';
require_once $root . '/routes/web.php';

// Component actions post back to the page they were rendered from, so they are
// answered before routing takes over.
if ($app->request->isPost() && class_exists(ActionDispatcher::class)) {
    $payload = $app->request->getJson() ?? $app->request->all();

    if (is_array($payload) && ActionDispatcher::handles($payload)) {
        try {
            $app->response->setHeader('Content-Type', 'text/html; charset=utf-8');
            $app->response->send(ActionDispatcher::dispatch($payload));
        } catch (RuntimeException $e) {
            $app->response->setStatusCode(403)->send($e->getMessage());
        } catch (InvalidArgumentException $e) {
            $app->response->setStatusCode(400)->send($e->getMessage());
        }

        return;
    }
}

$app->run();
