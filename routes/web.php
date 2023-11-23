<?php

use App\Exceptions\ScriptFailedException;
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

//todo for testing purposes
Route::get('/update/malware', function () {
    $service = new App\Ontologies\Malware\Service();
    try {
        return response()->json($service->updateMalware(new App\Ontologies\Malware\Parser()));
    } catch (ScriptFailedException $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'body' => $e->getBody(),
        ], 500);
    }

});

