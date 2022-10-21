<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function (Router $router) {
    $router->post('login', [AuthController::class, 'login']);
    $router->post('register', [AuthController::class, 'register']);
    $router->middleware('auth:api')->post('logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => 'auth:api'], function (Router $router) {
    $router->get('users', [UserController::class, 'index']);
    $router->get('users/{user}/tasks', [UserController::class, 'tasks']);

    $router->resource('tasks', TaskController::class);
    $router->post('tasks/{task}/assign-to-me', [TaskController::class, 'assignToMe']);
});

