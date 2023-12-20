<?php

use App\Exceptions\ScriptFailedException;
use App\Ontologies\Helpers\HttpService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;
use App\Livewire\Test;
use App\Livewire\Dashboard;
use App\Livewire\SearchHistory;

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

Route::get('/history', SearchHistory::class)->name('history');

Route::get('/about', function () {
    return view('about-page');
});
