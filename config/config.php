<?php

function getDatabaseDSN(): string
{
  if (isset($_ENV['DB_DSN'])) {
    $dsn = $_ENV['DB_DSN'];
    $dsn = trim($dsn, '"\'');
    return $dsn;
  }

  $dbname = $_ENV['DB_NAME'] ?? 'luxid';
  $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
  $port = $_ENV['DB_PORT'] ?? '3306';

  $sockets = [
    '/run/mysqld/mysqld.sock',
    '/var/run/mysqld/mysqld.sock',
    '/tmp/mysql.sock',
  ];

  foreach ($sockets as $socket) {
    if (file_exists($socket)) {
      return "mysql:unix_socket={$socket};dbname={$dbname}";
    }
  }

  return "mysql:host={$host};port={$port};dbname={$dbname}";
}

return [
  // Debug turns on stack traces in error pages and pretty printed JSON.
  // Keep it off in production: both leak internals and cost bandwidth.
  'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOL),

  'db' => [
    'dsn' => getDatabaseDSN(),
    'user' => $_ENV['DB_USER'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
  ],
  'userClass' => \App\Entities\User::class,

  // Origins permitted to call the API. Credentials are only advertised once
  // an explicit allowlist replaces the wildcard.
  'cors' => [
    'allowed_origins' => array_filter(explode(',', $_ENV['CORS_ALLOWED_ORIGINS'] ?? '*')),
    'supports_credentials' => filter_var($_ENV['CORS_CREDENTIALS'] ?? false, FILTER_VALIDATE_BOOL),
  ],

  // Rocket ORM settings
  'rocket' => [
    'cache' => [
      'enabled' => true,
      'path' => __DIR__ . '/../storage/framework/rocket',
    ],
    'migrations' => [
      'path' => __DIR__ . '/../migrations',
    ],
    'seeds' => [
      'path' => __DIR__ . '/../seeds',
    ],
  ],
];
