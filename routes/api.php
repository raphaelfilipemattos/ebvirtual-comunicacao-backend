<?php

use App\Http\Controllers\ResponsavelController;
use App\Services\Autenticacao\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/login', function (Request $request) {
   return ["token" =>  LoginService::fazLogin($request->input("email_cpf"),$request->input("senha"))];
});


Route::apiResource("responsavel",ResponsavelController::class)->middleware('auth:sanctum');
Route::put("alterarsenha/{id}",[ResponsavelController::class, "alterasenha"])->middleware('auth:sanctum');
Route::post("vinculaaluno",[ResponsavelController::class, "vinculaAluno"])->middleware('auth:sanctum');
Route::delete("desvinculaaluno/{id}",[ResponsavelController::class, "desvinculaAluno"])->middleware('auth:sanctum');
Route::get("parentescos",[ResponsavelController::class, "listaParenescos"]);