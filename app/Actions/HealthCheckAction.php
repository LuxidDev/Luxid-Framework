<?php

namespace App\Actions;

use Luxid\Nodes\Response;
use Luxid\Routing\Routes;

class HealthCheckAction extends LuxidAction
{
    public static function routes(): Routes
    {
        return Routes::new()
            ->prefix('api')
            ->add('/health', get('index'))
            ->public();
    }

    /**
     * Report that the application is up.
     *
     * GET /api/health
     */
    public function index(): string
    {
        return Response::success([
            'status' => 'healthy',
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
    }
}