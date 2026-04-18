<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DashboardPostController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', [BlogController::class, 'index'])->name('home');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function (): void {
        Route::redirect('/', '/dashboard/posts')->name('home');
        Route::resource('posts', DashboardPostController::class)->except('show');
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
