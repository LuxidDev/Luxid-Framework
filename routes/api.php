<?php
// Application Routes

use App\Actions\WelcomeAction;
use Luxid\Foundation\Application;

/** @var \Luxid\Routing\Router $router */
$router = Application::$app->router;

// Single welcome route
$router->get('/', [WelcomeAction::class, 'index']);
