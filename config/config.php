<?php
// Configuration File

return [
    'db' => [
        'dsn' => $_ENV['DB_DSN'] ?? 'mysql:host=127.0.0.1;port=3306;dbname=luxid_test',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
    ],
    'userClass' => \App\Entities\User::class,
];
