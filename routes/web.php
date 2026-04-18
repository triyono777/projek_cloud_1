<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome', [
        'title' => 'Projek Cloud 1',
        'subtitle' => 'Laravel + Docker + MySQL + Railway',
        'checks' => [
            'Framework' => app()->version(),
            'Environment' => app()->environment(),
            'Database driver' => config('database.default'),
            'App URL' => config('app.url'),
        ],
    ]);
});

Route::get('/health', function () {
    try {
        DB::connection()->getPdo();

        return response()->json([
            'status' => 'ok',
            'app' => config('app.name'),
            'environment' => app()->environment(),
            'database' => [
                'connection' => config('database.default'),
                'status' => 'connected',
            ],
        ]);
    } catch (\Throwable $exception) {
        return response()->json([
            'status' => 'degraded',
            'app' => config('app.name'),
            'environment' => app()->environment(),
            'database' => [
                'connection' => config('database.default'),
                'status' => 'disconnected',
                'message' => $exception->getMessage(),
            ],
        ], 500);
    }
});
