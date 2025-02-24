<?php

use App\Enums\StatePresetProduct;
use App\Http\Resources\WebsiteReservationResource;
use App\Imports\MessageHistoryImport;
use App\Models\Iamport;
use App\Models\Platform;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Milon\Barcode\DNS1D;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*Route::get("/test", [\App\Http\Controllers\Api\Admin\SurveyController::class, "export"]);*/

Route::get("/test", function (){
    $response = \Illuminate\Support\Facades\Http::post('http://localhost/api/login', [
        'email' => 'test@naver.com',
        'password' => 'test@naver.com',
    ]);

    dd($response->body());
});

Route::post("/users/import",function (Request $request){
    Excel::import(new \App\Imports\UserImport(), $request->file);
});

Route::get("/visits/export", [\App\Http\Controllers\Api\Admin\VisitController::class, "export"]);

Route::get('/', [\App\Http\Controllers\Api\PageController::class, "index"])->name("home");
Route::get('/home', [\App\Http\Controllers\Api\PageController::class, "index"]);


Route::middleware("guest")->group(function(){
    /*Route::get("/login", [\App\Http\Controllers\UserController::class, "loginForm"])->name("login");
    Route::get("/register", [\App\Http\Controllers\UserController::class, "create"]);
    Route::get("/openLoginPop/{social}", [\App\Http\Controllers\UserController::class, "openSocialLoginPop"]);
    Route::get("/login/{social}", [\App\Http\Controllers\UserController::class, "socialLogin"]);
    Route::post("/login", [\App\Http\Controllers\UserController::class, "login"]);
    Route::post("/register", [\App\Http\Controllers\UserController::class, "register"]);
    Route::resource("/users", \App\Http\Controllers\UserController::class);
    Route::get("/passwordResets/{token}/edit", [\App\Http\Controllers\PasswordResetController::class, "edit"]);
    Route::resource("/passwordResets", \App\Http\Controllers\PasswordResetController::class);
    */
});

Route::get("/users/export", [\App\Http\Controllers\Api\UserController::class, "export"]);
Route::middleware("auth")->group(function(){
    /*
    Route::get("/users/remove", [\App\Http\Controllers\UserController::class, "remove"]);
    Route::delete("/users", [\App\Http\Controllers\UserController::class, "destroy"]);
    Route::get("/users/edit", [\App\Http\Controllers\UserController::class, "edit"]);
    Route::post("/users/update", [\App\Http\Controllers\UserController::class, "update"]);

    Route::get("/logout", [\App\Http\Controllers\UserController::class, "logout"]);
    Route::get("/mypage", [\App\Http\Controllers\PageController::class, "mypage"]);
    */
});

Route::get("/mailable", function(){
    return (new \App\Mail\Sample(new \App\Models\User(), new \App\Models\PasswordReset()));
});

Route::prefix("/admin")->group(function(){

});

Route::get("/openLoginPop/{social}", [\App\Http\Controllers\Api\UserController::class, "openSocialLoginPop"]);
Route::get("/login/{social}", [\App\Http\Controllers\Api\UserController::class, "socialLogin"]);

Route::middleware("auth")->group(function(){

});

Route::get("/404", [\App\Http\Controllers\Api\ErrorController::class, "notFound"]);
Route::get("/403", [\App\Http\Controllers\Api\ErrorController::class, "unAuthenticated"]);
