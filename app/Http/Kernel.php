<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    //全局中间件，每次请求都会执行
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    //路由中间件，在路由里调用
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'SetSystemMiddleware' => \App\Http\Middleware\SetSystemMiddleware::class,
        'LoginMiddleware' => \App\Http\Middleware\LoginMiddleware::class,
        'RootMiddleware' => \App\Http\Middleware\RootMiddleware::class,
        'APIMiddleware' => \App\Http\Middleware\APIMiddleware::class,
        'AddCustMiddleware' => \App\Http\Middleware\AddCustMiddleware::class,
        'ServiceCareMiddleware' => \App\Http\Middleware\ServiceCareMiddleware::class,
        'DataStatisticsMiddleware' => \App\Http\Middleware\DataStatisticsMiddleware::class,
        'DataAnalysisMiddleware' => \App\Http\Middleware\DataAnalysisMiddleware::class,
        'CustManagementMiddleware' => \App\Http\Middleware\CustManagementMiddleware::class,
        'VoiceManagementMiddleware' => \App\Http\Middleware\VoiceManagementMiddleware::class,
    ];
}
