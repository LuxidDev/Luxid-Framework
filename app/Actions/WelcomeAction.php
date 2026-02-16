<?php
namespace App\Actions;

use App\Actions\LuxidAction;
use Luxid\Nodes\Nova;

class WelcomeAction extends LuxidAction
{
    public function index()
    {
        return Nova::render('welcome', [
            'title' => 'Welcome to Luxid Framework',
            'version' => '0.1.0',
            'phpVersion' => PHP_VERSION,
        ]);
    }
}
