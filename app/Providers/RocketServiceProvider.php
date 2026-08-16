<?php

declare(strict_types=1);

namespace App\Providers;

use Luxid\Foundation\Application;
use Rocket\Connection\Connection;

/**
 * Example application provider.
 *
 * Providers are discovered from `extra.luxid.providers` in composer.json, then
 * instantiated with no arguments and handed the application:
 *
 * ```php
 * public function register(Application $app): void
 * public function boot(Application $app): void
 * ```
 *
 * `register()` runs for every provider before any `boot()` does, so a provider
 * may depend on another's registrations during boot but not during register.
 *
 * The kernel already opens the Rocket connection from `config['db']`. This
 * provider only fills in when an application boots without that key.
 *
 * @package App\Providers
 */
class RocketServiceProvider
{
    /**
     * Bind services onto the application.
     *
     * @param Application $app The booting application
     */
    public function register(Application $app): void
    {
        if ($app->db !== null) {
            return;
        }

        $config = $this->databaseConfig($app);

        if ($config === null) {
            return;
        }

        Connection::initialize($config);
        $app->db = Connection::getInstance();
    }

    /**
     * Run once every provider has registered.
     *
     * @param Application $app The booting application
     */
    public function boot(Application $app): void
    {
    }

    /**
     * Resolve the database configuration.
     *
     * @param Application $app The booting application
     *
     * @return array{dsn: string, user: string, password: string}|null
     */
    protected function databaseConfig(Application $app): ?array
    {
        $configFile = $app::$ROOT_DIR . '/config/config.php';

        if (is_file($configFile)) {
            $config = require $configFile;

            if (isset($config['db'])) {
                return $config['db'];
            }
        }

        $dsn = $_ENV['DB_DSN'] ?? '';

        return $dsn === '' ? null : [
            'dsn' => $dsn,
            'user' => $_ENV['DB_USER'] ?? 'root',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
        ];
    }
}
