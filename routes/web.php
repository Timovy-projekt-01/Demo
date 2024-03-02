<?php

use App\Exceptions\ScriptFailedException;
use App\Ontologies\Helpers\HttpService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;
use App\Livewire\Test;
use App\Livewire\Dashboard;
use App\Livewire\SearchHistory;
use App\Livewire\UploadOntology;
use App\Livewire\Actions\Logout;

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
})->name('home')->middleware('lang');

Route::get('/history', SearchHistory::class)->name('history');

Route::get('/about', function () {
    return view('about-page');
})->middleware('lang');

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);

Route::get('/update', function(){
    return view('upload-page');
})->name('update');

//Route::post('/update/upload', [UploadOntology::class, 'uploadFile'])->name('upload');
Route::get('logout', function(Logout $logout) {
    $logout();
    return redirect(route('home'));
})->name('logout');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
