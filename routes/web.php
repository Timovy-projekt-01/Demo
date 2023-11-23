<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Test;
use App\Livewire\Dashboard;

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
//Route::get('/dashboard', SearchBar::class)->name('dashboard');
Route::get('/', function () {
    return view('main-page');
});

