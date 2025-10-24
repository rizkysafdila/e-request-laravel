<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Auth;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post('/auth/token', function () {
  abort_unless(Auth::user()->isAdmin(), 403);

  $payload = [
    'iss' => config('app.url'),
    'aud' => 'express-readonly',
    'iat' => time(),
    'exp' => time() + 3600, // 1 hour
    'scp' => ['read:requests'],
  ];
  $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
  return response()->json(['token' => $jwt]);
});
