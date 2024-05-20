<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\SearchHistory;
use App\Livewire\Actions\Logout;
use App\Livewire\ConfigEdit;
use App\Livewire\ConfigList;
use App\Ontologies\Handlers\Parser;
use App\Ontologies\Handlers\Service;

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
})->name('update')->middleware(['auth', 'lang']);

//Route::post('/update/upload', [UploadOntology::class, 'uploadFile'])->name('upload');
Route::get('logout', function(Logout $logout) {
    $logout();
    return redirect(route('home'));
})->name('logout');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/profile/configs', function(){
    return view('config-wrapper');
})->name('config-list')->middleware(['auth', 'lang']);
Route::get('/profile/configs/{config}', function(){
    return view('config-edit-wrapper');
})->name('config-edit')->middleware(['auth', 'lang']);

Route::get('/admin', function(){
    return view('admin-section');
})->name('admin');

require __DIR__.'/auth.php';
