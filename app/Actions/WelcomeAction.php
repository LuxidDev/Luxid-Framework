<?php

namespace App\Actions;

use Luxid\Nodes\Nova;
use Luxid\Routing\Routes;

class WelcomeAction extends LuxidAction
{
    public static function routes(): Routes
    {
        return Routes::new()
            ->add('/', get('index'))
            ->public();
    }

    /**
     * Render the welcome page.
     *
     * GET /
     */
    public function index(): string
    {
        return Nova::render('Welcome', [
            'title' => 'Welcome to Luxid Framework',
            'version' => '0.5.0',
            'phpVersion' => PHP_VERSION,
        ]);
    }
}