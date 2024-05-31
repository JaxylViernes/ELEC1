<?php


use App\Http\Controllers\PlantController;
use Illuminate\Support\Facades\Route;



Route::get('/index', function () {
    return view('upload');
})->name('index');
Route::get('/', function ()  {
    return view('homepage',  ['facts' => app(PlantController::class)->facts()]);
})->name('home');



// Route for identifying the plant
Route::post('/plant/result', [PlantController::class, 'identifyPlant'])->name('plant.result');

// Route for saving the plant details
Route::post('/upload', [PlantController::class, 'store'])->name('plant.save');
Route::get('/plant/display', [PlantController::class, 'index'])->name('plant.display');


Route::delete('/plant/{plant}', [PlantController::class, 'destroy'])->name('plant.delete');

Route::get('/homepage', [PlantController::class, 'facts']);


