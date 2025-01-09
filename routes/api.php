<?php

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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/



Route::post("/images", [\App\Http\Controllers\Api\ImageController::class, "store"]);
Route::post("/imageUpload", [\App\Http\Controllers\Api\ImageController::class, "store"]);

Route::middleware("admin")->prefix("/admin")->group(function () {
    Route::patch("/examples/{example}/up", [\App\Http\Controllers\Api\Admin\ExampleController::class, "up"]);
    Route::patch("/examples/{example}/down", [\App\Http\Controllers\Api\Admin\ExampleController::class, "down"]);
    Route::delete("/examples", [\App\Http\Controllers\Api\Admin\ExampleController::class, "destroy"]);
    Route::resource("/examples", \App\Http\Controllers\Api\Admin\ExampleController::class);

    Route::resource("/banners", \App\Http\Controllers\Api\Admin\BannerController::class)->except(['destroy']);
    Route::delete("/banners", [\App\Http\Controllers\Api\Admin\BannerController::class, "destroy"]);

    Route::patch("/categories/up/{category}", [\App\Http\Controllers\Api\Admin\CategoryController::class, "up"]);
    Route::patch("/categories/down/{category}", [\App\Http\Controllers\Api\Admin\CategoryController::class, "down"]);
    Route::resource("/categories", \App\Http\Controllers\Api\Admin\CategoryController::class)->except(['destroy']);
    Route::delete("/categories", [\App\Http\Controllers\Api\Admin\CategoryController::class, "destroy"]);

    // Route::resource("/recommendCategories", \App\Http\Controllers\Api\Admin\RecommendCategoryController::class)->except(['destroy']);
    // Route::delete("/recommendCategories", [\App\Http\Controllers\Api\Admin\RecommendCategoryController::class, "destroy"]);

    Route::resource("/couponGroups", \App\Http\Controllers\Api\Admin\CouponGroupController::class)->except(['destroy']);
    Route::delete("/couponGroups", [\App\Http\Controllers\Api\Admin\CouponGroupController::class, "destroy"]);


    Route::resource("/events", \App\Http\Controllers\Api\Admin\EventController::class)->except(['destroy']);
    Route::delete("/events", [\App\Http\Controllers\Api\Admin\EventController::class, "destroy"]);

    Route::resource("/faqs", \App\Http\Controllers\Api\Admin\FaqController::class)->except(['destroy']);
    Route::delete("/faqs", [\App\Http\Controllers\Api\Admin\FaqController::class, "destroy"]);

    Route::resource("/notices", \App\Http\Controllers\Api\Admin\NoticeController::class)->except(['destroy']);
    Route::delete("/notices", [\App\Http\Controllers\Api\Admin\NoticeController::class, "destroy"]);

    Route::resource("/users", \App\Http\Controllers\Api\Admin\UserController::class)->except(['destroy', 'store', 'update']);
    Route::delete("/users", [\App\Http\Controllers\Api\Admin\UserController::class, "destroy"]);

    // Route::resource("/estimates", \App\Http\Controllers\Api\Admin\EstimateController::class)->except(['destroy', 'store', 'update']);
    // Route::delete("/estimates", [\App\Http\Controllers\Api\Admin\EstimateController::class, "destroy"]);

    Route::resource("/qnas", \App\Http\Controllers\Api\Admin\QnaController::class)->except(['destroy', 'store']);
    Route::delete("/qnas", [\App\Http\Controllers\Api\Admin\QnaController::class, "destroy"]);

    Route::patch("/products/active", [\App\Http\Controllers\Api\Admin\ProductController::class, "updateActive"]);
    Route::patch("/products/up/{product}", [\App\Http\Controllers\Api\Admin\ProductController::class, "up"]);
    Route::patch("/products/down/{product}", [\App\Http\Controllers\Api\Admin\ProductController::class, "down"]);
    Route::resource("/products", \App\Http\Controllers\Api\Admin\ProductController::class)->except(['destroy']);
    Route::delete("/products", [\App\Http\Controllers\Api\Admin\ProductController::class, "destroy"]);

    Route::resource("/reviews", \App\Http\Controllers\Api\Admin\ReviewController::class)->except(['destroy']);
    Route::delete("/reviews", [\App\Http\Controllers\Api\Admin\ReviewController::class, "destroy"]);

    // Route::resource("/prototypes", \App\Http\Controllers\Api\Admin\PrototypeController::class)->except(['destroy']);
    // Route::delete("/prototypes", [\App\Http\Controllers\Api\Admin\PrototypeController::class, "destroy"]);

    // Route::patch("/feedbacks/check/{feedback}", [\App\Http\Controllers\Api\Admin\FeedbackController::class, "check"]);
    // Route::resource("/feedbacks", \App\Http\Controllers\Api\Admin\FeedbackController::class)->except(['destroy']);
    // Route::delete("/feedbacks", [\App\Http\Controllers\Api\Admin\FeedbackController::class, "destroy"]);

    Route::patch("/orders/cancel/{order}", [\App\Http\Controllers\Api\Admin\OrderController::class, 'cancel']);
    Route::resource("/orders", \App\Http\Controllers\Api\Admin\OrderController::class)->except(['destroy']);
    Route::delete("/orders", [\App\Http\Controllers\Api\Admin\OrderController::class, "destroy"]);

    Route::patch("/presetProducts/needAlertDelivery/{presetProduct}", [\App\Http\Controllers\Api\Admin\PresetProductController::class, "updateNeedAlertDelivery"]);
    Route::patch("/presetProducts/state/{presetProduct}", [\App\Http\Controllers\Api\Admin\PresetProductController::class, "updateState"]);
    Route::patch("/presetProducts/willPrototypeFinishedAt/{presetProduct}", [\App\Http\Controllers\Api\Admin\PresetProductController::class, "updateWillPrototypeFinishedAt"]);
    Route::resource("/presetProducts", \App\Http\Controllers\Api\Admin\PresetProductController::class)->except(['destroy']);
    Route::delete("/presetProducts", [\App\Http\Controllers\Api\Admin\PresetProductController::class, "destroy"]);

    Route::resource("/refunds", \App\Http\Controllers\Api\Admin\RefundController::class)->except(['destroy']);
    Route::delete("/refunds", [\App\Http\Controllers\Api\Admin\RefundController::class, "destroy"]);

    Route::resource("/pointHistories", \App\Http\Controllers\Api\Admin\PointHistoryController::class)->except(['destroy']);
    Route::delete("/pointHistories", [\App\Http\Controllers\Api\Admin\PointHistoryController::class, "destroy"]);


});

Route::post("/login", [\App\Http\Controllers\Api\UserController::class, "login"]);
Route::post("/users", [\App\Http\Controllers\Api\UserController::class, "store"]);

Route::post("/findIds", [\App\Http\Controllers\Api\FindIdController::class, "store"]);
Route::post("/findPasswords", [\App\Http\Controllers\Api\FindPasswordController::class, "store"]);

Route::get("/payMethods", [\App\Http\Controllers\Api\PayMethodController::class, "index"]);
Route::get("/orders/bill",[\App\Http\Controllers\Api\OrderController::class, 'bill']);
Route::get("/orders/guest", [\App\Http\Controllers\Api\OrderController::class, "showByGuest"]);
Route::post("/orders/complete/webhook", [\App\Http\Controllers\Api\OrderController::class, "complete"]);
Route::post("/orders/complete", [\App\Http\Controllers\Api\OrderController::class, "complete"]);
Route::get("/orders/complete/mobile", [\App\Http\Controllers\Api\OrderController::class, "complete"]);
Route::patch("/orders/{order}",[\App\Http\Controllers\Api\OrderController::class, 'update']);
Route::get("/orders/{order}",[\App\Http\Controllers\Api\OrderController::class, 'show']);
Route::post("/orders",[\App\Http\Controllers\Api\OrderController::class, 'store']);
Route::get("/reviews", [\App\Http\Controllers\Api\ReviewController::class, "index"]);

Route::get("/carts", [\App\Http\Controllers\Api\CartController::class, 'index']);
Route::post("/carts", [\App\Http\Controllers\Api\CartController::class, 'store']);
Route::delete("/carts", [\App\Http\Controllers\Api\CartController::class, 'destroy']);
Route::patch("/carts", [\App\Http\Controllers\Api\CartController::class, 'update']);

Route::get("/events", [\App\Http\Controllers\Api\EventController::class, "index"]);
Route::get("/events/{event}", [\App\Http\Controllers\Api\EventController::class, "show"]);

Route::get("/faqs", [\App\Http\Controllers\Api\FaqController::class, "index"]);

Route::get("/notices", [\App\Http\Controllers\Api\NoticeController::class, "index"]);
Route::get("/notices/{notice}", [\App\Http\Controllers\Api\NoticeController::class, "show"]);

Route::get("/products", [\App\Http\Controllers\Api\ProductController::class, "index"]);
Route::get("/products/{product}", [\App\Http\Controllers\Api\ProductController::class, "show"]);

Route::get("/categories", [\App\Http\Controllers\Api\CategoryController::class, "index"]);
// Route::get("/recommendCategories", [\App\Http\Controllers\Api\RecommendCategoryController::class, "index"]);

Route::get("/banners", [\App\Http\Controllers\Api\BannerController::class, "index"]);
Route::get("/pops", [\App\Http\Controllers\Api\PopController::class, "index"]);
Route::get("/counts", [\App\Http\Controllers\Api\CountController::class, "index"]);
Route::get("/cities", [\App\Http\Controllers\Api\CityController::class, "index"]);
Route::get("/tags", [\App\Http\Controllers\Api\TagController::class, "index"]);
Route::get("/recipes", [\App\Http\Controllers\Api\RecipeController::class, "index"]);
Route::get("/farmStories", [\App\Http\Controllers\Api\FarmStoryController::class, "index"]);
Route::get("/farmStories/{farmStory}", [\App\Http\Controllers\Api\FarmStoryController::class, "show"]);


Route::get("/presetProducts", [\App\Http\Controllers\Api\PresetProductController::class, "index"]);
Route::get("/presetProducts/{uuid}", [\App\Http\Controllers\Api\PresetProductController::class, "show"]);
Route::delete("/presetProducts/{uuid}", [\App\Http\Controllers\Api\PresetProductController::class, "destroy"]);
Route::patch("/presetProducts/{uuid}", [\App\Http\Controllers\Api\PresetProductController::class, "update"]);
Route::patch("/presetProducts/count/{uuid}", [\App\Http\Controllers\Api\PresetProductController::class, "updateCount"]);

Route::get("/presets", [\App\Http\Controllers\Api\PresetController::class, "index"]);
Route::post("/presets", [\App\Http\Controllers\Api\PresetController::class, "store"]);


Route::post('/verifyNumbers', [\App\Http\Controllers\Api\VerifyNumberController::class, "store"]);
Route::patch('/verifyNumbers', [\App\Http\Controllers\Api\VerifyNumberController::class, "update"]);

Route::get('/deliveries', [\App\Http\Controllers\Api\DeliveryController::class, "index"]);
Route::get('/deliveries/{delivery}', [\App\Http\Controllers\Api\DeliveryController::class, "show"]);
Route::post('/deliveries', [\App\Http\Controllers\Api\DeliveryController::class, "store"]);
Route::patch('/deliveries/{delivery}', [\App\Http\Controllers\Api\DeliveryController::class, "update"]);
Route::delete('/deliveries/{delivery}', [\App\Http\Controllers\Api\DeliveryController::class, "destroy"]);
Route::patch("/users/clearPassword", [\App\Http\Controllers\Api\UserController::class, "clearPassword"]);
Route::patch("/users/findId", [\App\Http\Controllers\Api\UserController::class, "findId"]);

Route::middleware(['auth'])->group(function () {
    Route::get("/user", [\App\Http\Controllers\Api\UserController::class, 'show']);

    Route::patch("/users", [\App\Http\Controllers\Api\UserController::class, "update"]);
    Route::patch("/users/codeRecommend", [\App\Http\Controllers\Api\UserController::class, "updateCodeRecommend"]);
    Route::patch("/users/password", [\App\Http\Controllers\Api\UserController::class, "updatePassword"]);

    Route::delete("/users", [\App\Http\Controllers\Api\UserController::class, "destroy"]);
    Route::get("/logout", [\App\Http\Controllers\Api\UserController::class, "logout"]);
    Route::post("/logout", [\App\Http\Controllers\Api\UserController::class, "logout"]);

    Route::post("/qnas", [\App\Http\Controllers\Api\QnaController::class, "store"]);
    Route::patch("/qnas/{qna}", [\App\Http\Controllers\Api\QnaController::class, "update"]);

    Route::post("/products", [\App\Http\Controllers\Api\ProductController::class, "store"]);
    Route::patch("/products/{product}", [\App\Http\Controllers\Api\ProductController::class, "update"]);
    Route::delete("/products/{product}", [\App\Http\Controllers\Api\ProductController::class, "destroy"]);

    Route::patch("/orders/cancel/{order}",[\App\Http\Controllers\Api\OrderController::class, 'cancel']);
    Route::get("/orders",[\App\Http\Controllers\Api\OrderController::class, 'index']);


    Route::get("/refunds",[\App\Http\Controllers\Api\RefundController::class, 'index']);
    Route::post("/refunds",[\App\Http\Controllers\Api\RefundController::class, 'store']);


    Route::post("/reviews", [\App\Http\Controllers\Api\ReviewController::class, "store"]);
    Route::patch("/reviews/{review}", [\App\Http\Controllers\Api\ReviewController::class, "update"]);
    Route::delete("/reviews/{review}", [\App\Http\Controllers\Api\ReviewController::class, "destroy"]);

    Route::patch("/presetProducts/confirm/{presetProduct}", [\App\Http\Controllers\Api\PresetProductController::class, "confirm"]);

    Route::get('/pointHistories', [\App\Http\Controllers\Api\PointHistoryController::class, "index"]);
    Route::get('/couponHistories', [\App\Http\Controllers\Api\CouponHistoryController::class, "index"]);

    Route::get("/qnaCategories", [\App\Http\Controllers\Api\QnaCategoryController::class, "index"]);
    Route::get("/qnas", [\App\Http\Controllers\Api\QnaController::class, "index"]);

    Route::get('/coupons', [\App\Http\Controllers\Api\CouponController::class, "index"]);
    Route::post("/coupons", [\App\Http\Controllers\Api\CouponController::class, "store"]);

    Route::post("/likes", [\App\Http\Controllers\Api\LikeController::class, "store"]);
    Route::post("/bookmarks", [\App\Http\Controllers\Api\BookmarkController::class, "store"]);
});




