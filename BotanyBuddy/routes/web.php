<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;

// Route for showing the upload form
Route::get('/upload', function () {
    return view('upload');
})->name('upload.form');

// Route for identifying the plant
Route::post('/identify-plant', [PlantController::class, 'identifyPlant'])->name('identify.plant');

// Route for saving the plant details
Route::post('/save-plant', [PlantController::class, 'savePlant'])->name('plant.save');
