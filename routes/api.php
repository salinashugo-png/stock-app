<?php

use App\Http\Controllers\MovementController;
use App\Http\Controllers\ProductController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::post('/login', function (Request $r) {
    $r->validate(['email' => 'required|email', 'password' => 'required']);
    $u = User::where('email', $r->email)->first();
    if (!$u || !Hash::check($r->password, $u->password)) {
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }
    return ['token' => $u->createToken('postman')->plainTextToken];
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/stock', [ProductController::class, 'stock']);
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/movements', [MovementController::class, 'index']);
    Route::post('/movements', [MovementController::class, 'store']);

    Route::middleware('role:admin')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    });
});
