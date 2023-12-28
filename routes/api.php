<?php
use \App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use \App\Http\Controllers\MedicineController;
use Illuminate\Http\Request;
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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/login" , [UserController::class , "login"]);

Route::post("/register" , [UserController::class , "store"]);

Route::middleware("auth:sanctum")
    ->post("/logout", [UserController::class , "logout"]);

Route::middleware("auth:sanctum")
    ->get("/medicines/search" , [MedicineController::class , "search"]);

Route::middleware(["auth:sanctum"])->group(function (){
        Route::apiResources([
            "medicines" => MedicineController::class,
            "orders" => OrderController::class,
            "users" => UserController::class,
        ]);
    }
);

