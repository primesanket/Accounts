<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/reset-database', function () {
    if (app()->environment('production')) {
        // Fresh migration with seeding
        Artisan::call('migrate:fresh --seed');
        
        return 'Database reset, seeded!';
    }

    abort(403, 'Forbidden.');
});
