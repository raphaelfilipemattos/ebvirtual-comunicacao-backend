<?php

use App\Http\Controllers\ResponsavelController;
use App\Models\Responsavel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource("responsavel",ResponsavelController::class);
Route::put("alterarsenha/{id}",[ResponsavelController::class, "alterasenha"]);