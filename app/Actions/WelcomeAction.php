<?php

namespace App\Actions;

use Luxid\Foundation\Action;

class WelcomeAction extends Action
{
    public function index()
    {
        return $this->nova('welcome', [
            'title' => 'Welcome to Luxid Framework',
            'version' => '0.1.0',
            'phpVersion' => PHP_VERSION,
        ]);
    }
}
