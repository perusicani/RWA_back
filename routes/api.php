<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CheckpointController;
use App\Http\Controllers\SkillController;

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

// Public api routes
Route::post('/register' , [AuthController::class, 'register']);
Route::post('/login' , [AuthController::class, 'login']);


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['auth:sanctum', 'isAPIAdmin']], function () {
    // Protected admin api routes
    
    Route::get('/checkingAuthenticated', function() {
        $response= [
            'message' => 'You are in!'
        ];  
        return response($response, 200);
    });
 
    //admin only
    Route::get('/users', [UserController::class, 'index']);
    // Route::get('/users/{user}', [UserController::class, 'show']);
    // Route::get('/users/{id}', [UserController::class, 'show']);
    Route::delete('/users/delete/{id}', [UserController::class, 'destroy']);
    
    Route::post('/skills/create', [SkillController::class, 'create']);
    Route::get('/skills', [SkillController::class, 'index']);
    Route::get('/skills/{skill}', [SkillController::class, 'show']);
    Route::post('/skills', [SkillController::class, 'update']);
    Route::delete('/skills/{id}', [SkillController::class, 'destroy']);
});


Route::group(['middleware' => ['auth:sanctum']], function () {
    // Protected user api routes
    
    Route::post('/logout' , [AuthController::class, 'logout']);
    
    //any user
    Route::post('/tasks/create', [TaskController::class, 'create']);
    Route::get('/tasks', [TaskController::class, 'index']);
    // WILDCARDS ARE UNSPECIFIED
    // Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::get('/tasks/{id}', [TaskController::class, 'id']);
    Route::post('/tasks', [TaskController::class, 'update']);
    Route::delete('/tasks/delete/{id}', [TaskController::class, 'destroy']);
    Route::post('/tasks/add-skills', [TaskController::class, 'addSkills']);
    
    Route::post('/checkpoints/create', [CheckpointController::class, 'create']);
    Route::get('/checkpoints', [CheckpointController::class, 'index']);
    Route::get('/checkpoints/{checkpoint}', [CheckpointController::class, 'show']);
    Route::post('/checkpoint', [CheckpointController::class, 'updateOne']);
    Route::post('/checkpoints', [CheckpointController::class, 'update']);
    Route::delete('/checkpoints/{id}', [CheckpointController::class, 'destroy']);
    
    Route::post('/users', [UserController::class, 'update']);
    Route::get('/users/{id}', [UserController::class, 'id']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/users/add-skills', [UserController::class, 'addSkills']);
});