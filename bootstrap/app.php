<?php

use App\Http\Middleware\ClanPreholder;
use App\Http\Middleware\InfluencersAdminAuth;
use App\Http\Middleware\MaintenanceMode;
use App\Http\Middleware\ReservasAdminAuth;
use App\Http\Middleware\ReservasReviewAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'showclinic.admin' => \App\Http\Middleware\ShowClinicAdminAuth::class,
            'reservas.admin' => ReservasAdminAuth::class,
            'reservas.review' => ReservasReviewAuth::class,
            'influencers.admin' => InfluencersAdminAuth::class,
        ]);

        $middleware->web(append: [
            MaintenanceMode::class,
            ClanPreholder::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
