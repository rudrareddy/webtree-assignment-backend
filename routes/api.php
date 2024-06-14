<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ExpenseController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/


// authentications routes
Route::post('/register', [AuthController::class, 'store']);
Route::post('/login',[AuthController::class,'login']);

// protected routes with sanctum
Route::group(['middleware' => ['auth:sanctum']],function () {

  Route::get('user', function(Request $request) {
      return [
          'user' => $request->user(),
          'currentToken' => $request->bearerToken()
      ];
  });
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get("/refresh", [AuthController::class, 'refresh']);
    Route::get("/profile", [AuthController::class, 'profile']);
    // Update Existing User password basis on Old Password and New Password
    Route::post('/change-password',[AuthController::class,'updatePassword']);

    //categories routes
    Route::apiResource('/categories', CategoryController::class);
    //expenses route
    Route::apiResource('/expenses', ExpenseController::class);
    //filters
    Route::get('filter-category/{id}',[ExpenseController::class,'filter_category']);
    Route::post('filter-daterange/',[ExpenseController::class,'filter_date_range']);
});
