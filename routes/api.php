<?php

use App\Http\Controllers\Api\QnaController;
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



Route::get("/imp", [\App\Http\Controllers\Api\ImpController::class, "index"]);
Route::post("/images", [\App\Http\Controllers\Api\ImageController::class, "store"]);
Route::post("/imageUpload", [\App\Http\Controllers\Api\ImageController::class, "store"]);

Route::middleware("admin")->prefix("/admin")->group(function () {
    Route::patch("/examples/{example}/up", [\App\Http\Controllers\Api\Admin\ExampleController::class, "up"]);
    Route::patch("/examples/{example}/down", [\App\Http\Controllers\Api\Admin\ExampleController::class, "down"]);
    Route::delete("/examples", [\App\Http\Controllers\Api\Admin\ExampleController::class, "destroy"]);
    Route::resource("/examples", \App\Http\Controllers\Api\Admin\ExampleController::class);

    Route::resource("/deliveryCompanies", \App\Http\Controllers\Api\Admin\DeliveryCompanyController::class)->except(['destroy']);
    Route::delete("/deliveryCompanies", [\App\Http\Controllers\Api\Admin\DeliveryCompanyController::class, "destroy"]);

    Route::patch("/banners/{banner}/up", [\App\Http\Controllers\Api\Admin\BannerController::class, "up"]);
    Route::patch("/banners/{banner}/down", [\App\Http\Controllers\Api\Admin\BannerController::class, "down"]);
    Route::resource("/banners", \App\Http\Controllers\Api\Admin\BannerController::class);

    Route::patch("/pops/{pop}/up", [\App\Http\Controllers\Api\Admin\PopController::class, "up"]);
    Route::patch("/pops/{pop}/down", [\App\Http\Controllers\Api\Admin\PopController::class, "down"]);
    Route::resource("/pops", \App\Http\Controllers\Api\Admin\PopController::class);

    Route::delete("/reports", [\App\Http\Controllers\Api\Admin\ReportController::class, "destroy"]);
    Route::resource("/reports", \App\Http\Controllers\Api\Admin\ReportController::class);

    Route::delete("/pointHistories", [\App\Http\Controllers\Api\Admin\PointHistoryController::class, "destroy"]);
    Route::resource("/pointHistories", \App\Http\Controllers\Api\Admin\PointHistoryController::class);

    Route::delete("/memos", [\App\Http\Controllers\Api\Admin\MemoController::class, "destroy"]);
    Route::resource("/memos", \App\Http\Controllers\Api\Admin\MemoController::class);

    Route::delete("/deliveries", [\App\Http\Controllers\Api\Admin\DeliveryController::class, "destroy"]);
    Route::resource("/deliveries", \App\Http\Controllers\Api\Admin\DeliveryController::class)->except(['destroy']);

    Route::delete("/coupons", [\App\Http\Controllers\Api\Admin\CouponController::class, "destroy"]);
    Route::resource("/coupons", \App\Http\Controllers\Api\Admin\CouponController::class);


    Route::resource("/banners", \App\Http\Controllers\Api\Admin\BannerController::class)->except(['destroy']);
    Route::delete("/banners", [\App\Http\Controllers\Api\Admin\BannerController::class, "destroy"]);

    Route::patch("/categories/up/{category}", [\App\Http\Controllers\Api\Admin\CategoryController::class, "up"]);
    Route::patch("/categories/down/{category}", [\App\Http\Controllers\Api\Admin\CategoryController::class, "down"]);
    Route::resource("/categories", \App\Http\Controllers\Api\Admin\CategoryController::class)->except(['destroy']);
    Route::delete("/categories", [\App\Http\Controllers\Api\Admin\CategoryController::class, "destroy"]);

    // Route::resource("/recommendCategories", \App\Http\Controllers\Api\Admin\RecommendCategoryController::class)->except(['destroy']);
    // Route::delete("/recommendCategories", [\App\Http\Controllers\Api\Admin\RecommendCategoryController::class, "destroy"]);

    Route::patch("/tags/{tag}/up", [\App\Http\Controllers\Api\Admin\TagController::class, "up"]);
    Route::patch("/tags/{tag}/down", [\App\Http\Controllers\Api\Admin\TagController::class, "down"]);
    Route::patch("/tags/open", [\App\Http\Controllers\Api\Admin\TagController::class, "open"]);
    Route::resource("/tags", \App\Http\Controllers\Api\Admin\TagController::class)->except(['destroy']);
    Route::delete("/tags", [\App\Http\Controllers\Api\Admin\TagController::class, "destroy"]);

    Route::resource("/materials", \App\Http\Controllers\Api\Admin\MaterialController::class)->except(['destroy']);
    Route::delete("/materials", [\App\Http\Controllers\Api\Admin\MaterialController::class, "destroy"]);

    Route::get("/presetProducts/materials", [\App\Http\Controllers\Api\Admin\PresetProductController::class, 'materials']);
    Route::post("/presetProducts/materials/export", [\App\Http\Controllers\Api\Admin\PresetProductController::class, 'exportMaterials']);
    Route::get("/presetProducts/counts", [\App\Http\Controllers\Api\Admin\PresetProductController::class, 'counts']);
    Route::post("/presetProducts/export", [\App\Http\Controllers\Api\Admin\PresetProductController::class, 'export']);
    Route::post("/presetProducts/import", [\App\Http\Controllers\Api\Admin\PresetProductController::class, 'import']);
    Route::patch("/presetProducts/state/{presetProduct}", [\App\Http\Controllers\Api\Admin\PresetProductController::class, 'updateState']);
    Route::patch("/presetProducts/cancel/{presetProduct}", [\App\Http\Controllers\Api\Admin\PresetProductController::class, 'cancel']);
    Route::patch("/presetProducts/deliveryAddress/{presetProduct}", [\App\Http\Controllers\Api\Admin\PresetProductController::class, 'updateDeliveryAddress']);
    Route::patch("/presetProducts/willOut", [\App\Http\Controllers\Api\Admin\PresetProductController::class, 'willOut']);
    Route::patch("/presetProducts/schedule/{packageSetting}", [\App\Http\Controllers\Api\Admin\PresetProductController::class, 'updateSchedule']);
    Route::resource("/presetProducts", \App\Http\Controllers\Api\Admin\PresetProductController::class)->except(['destroy']);
    Route::delete("/presetProducts", [\App\Http\Controllers\Api\Admin\PresetProductController::class, "destroy"]);

    Route::resource("/couponGroups", \App\Http\Controllers\Api\Admin\CouponGroupController::class)->except(['destroy']);
    Route::delete("/couponGroups", [\App\Http\Controllers\Api\Admin\CouponGroupController::class, "destroy"]);

    Route::get("/count", [\App\Http\Controllers\Api\Admin\CountController::class, 'show']);
    Route::post("/counts", [\App\Http\Controllers\Api\Admin\CountController::class, 'store']);

    Route::patch("/packages/schedule/{package}", [\App\Http\Controllers\Api\Admin\PackageController::class, 'updateSchedule']);
    Route::resource("/packages", \App\Http\Controllers\Api\Admin\PackageController::class)->except(['destroy']);
    Route::delete("/packages", [\App\Http\Controllers\Api\Admin\PackageController::class, "destroy"]);

    Route::resource("/events", \App\Http\Controllers\Api\Admin\EventController::class)->except(['destroy']);
    Route::delete("/events", [\App\Http\Controllers\Api\Admin\EventController::class, "destroy"]);

    Route::resource("/faqs", \App\Http\Controllers\Api\Admin\FaqController::class)->except(['destroy']);
    Route::delete("/faqs", [\App\Http\Controllers\Api\Admin\FaqController::class, "destroy"]);

    Route::resource("/notices", \App\Http\Controllers\Api\Admin\NoticeController::class)->except(['destroy']);
    Route::delete("/notices", [\App\Http\Controllers\Api\Admin\NoticeController::class, "destroy"]);

    Route::post("/users/export", [\App\Http\Controllers\Api\Admin\UserController::class, "export"]);
    Route::resource("/users", \App\Http\Controllers\Api\Admin\UserController::class)->except(['destroy']);
    Route::delete("/users/{user}", [\App\Http\Controllers\Api\Admin\UserController::class, "destroy"]);
    Route::get("/users/counts/{user}", [\App\Http\Controllers\Api\Admin\UserController::class, "counts"]);

    // Route::resource("/estimates", \App\Http\Controllers\Api\Admin\EstimateController::class)->except(['destroy', 'store', 'update']);
    // Route::delete("/estimates", [\App\Http\Controllers\Api\Admin\EstimateController::class, "destroy"]);

    Route::resource("/qnas", \App\Http\Controllers\Api\Admin\QnaController::class)->except(['destroy', 'store']);
    Route::delete("/qnas", [\App\Http\Controllers\Api\Admin\QnaController::class, "destroy"]);

    Route::delete("/recipes", [\App\Http\Controllers\Api\Admin\RecipeController::class, "destroy"]);
    Route::resource("/recipes", \App\Http\Controllers\Api\Admin\RecipeController::class)->except(['destroy']);

    Route::patch("/products/active", [\App\Http\Controllers\Api\Admin\ProductController::class, "updateActive"]);
    Route::patch("/products/up/{product}", [\App\Http\Controllers\Api\Admin\ProductController::class, "up"]);
    Route::patch("/products/down/{product}", [\App\Http\Controllers\Api\Admin\ProductController::class, "down"]);
    Route::resource("/products", \App\Http\Controllers\Api\Admin\ProductController::class)->except(['destroy']);
    Route::delete("/products", [\App\Http\Controllers\Api\Admin\ProductController::class, "destroy"]);

    Route::get("/vegetableStories/counts", [\App\Http\Controllers\Api\Admin\VegetableStoryController::class, 'counts']);
    Route::resource("/vegetableStories", \App\Http\Controllers\Api\Admin\VegetableStoryController::class)->except(['destroy']);
    Route::delete("/vegetableStories", [\App\Http\Controllers\Api\Admin\VegetableStoryController::class, "destroy"]);

    Route::resource("/comments", \App\Http\Controllers\Api\Admin\CommentController::class)->except(['destroy']);
    Route::delete("/comments", [\App\Http\Controllers\Api\Admin\CommentController::class, "destroy"]);

    Route::get("/reviews/counts", [\App\Http\Controllers\Api\Admin\ReviewController::class, 'counts']);
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


    /*Route::patch("/presetProducts/needAlertDelivery/{presetProduct}", [\App\Http\Controllers\Api\Admin\PresetProductController::class, "updateNeedAlertDelivery"]);
    Route::patch("/presetProducts/state/{presetProduct}", [\App\Http\Controllers\Api\Admin\PresetProductController::class, "updateState"]);
    Route::patch("/presetProducts/willPrototypeFinishedAt/{presetProduct}", [\App\Http\Controllers\Api\Admin\PresetProductController::class, "updateWillPrototypeFinishedAt"]);
    Route::resource("/presetProducts", \App\Http\Controllers\Api\Admin\PresetProductController::class)->except(['destroy']);
    Route::delete("/presetProducts", [\App\Http\Controllers\Api\Admin\PresetProductController::class, "destroy"]);*/

    Route::resource("/refunds", \App\Http\Controllers\Api\Admin\RefundController::class)->except(['destroy']);
    Route::delete("/refunds", [\App\Http\Controllers\Api\Admin\RefundController::class, "destroy"]);

    Route::resource("/bookmarks", \App\Http\Controllers\Api\Admin\BookmarkController::class)->except(['destroy']);
    Route::delete("/bookmarks", [\App\Http\Controllers\Api\Admin\BookmarkController::class, "destroy"]);

    Route::resource("/pointHistories", \App\Http\Controllers\Api\Admin\PointHistoryController::class)->except(['destroy']);
    Route::delete("/pointHistories", [\App\Http\Controllers\Api\Admin\PointHistoryController::class, "destroy"]);

    Route::resource("/farms", \App\Http\Controllers\Api\Admin\FarmController::class)->except(['destroy']);
    Route::delete("/farms", [\App\Http\Controllers\Api\Admin\FarmController::class, "destroy"]);

    Route::resource("/projects", \App\Http\Controllers\Api\Admin\ProjectController::class)->except(['destroy']);
    Route::delete("/projects", [\App\Http\Controllers\Api\Admin\ProjectController::class, "destroy"]);

    Route::resource("/farmStories", \App\Http\Controllers\Api\Admin\FarmStoryController::class)->except(['destroy']);
    Route::delete("/farmStories", [\App\Http\Controllers\Api\Admin\FarmStoryController::class, "destroy"]);

    Route::get("/deliverySetting", [\App\Http\Controllers\Api\Admin\DeliverySettingController::class, 'show']);
    Route::resource("/deliverySettings", \App\Http\Controllers\Api\Admin\DeliverySettingController::class)->except(['show', 'destroy']);
    Route::delete("/deliverySettings", [\App\Http\Controllers\Api\Admin\DeliverySettingController::class, "destroy"]);

    Route::get('/grades', [\App\Http\Controllers\Api\Admin\GradeController::class, "index"]);

    Route::get("/couponGroups", [\App\Http\Controllers\Api\Admin\CouponGroupController::class, 'show']);
    Route::resource("/couponGroups", \App\Http\Controllers\Api\Admin\CouponGroupController::class)->except(['show', 'destroy']);

    Route::get('/dashboard', [\App\Http\Controllers\Api\Admin\DashboardController::class, "matrix"]);
});

Route::post("/login", [\App\Http\Controllers\Api\UserController::class, "login"]);
Route::post("/users", [\App\Http\Controllers\Api\UserController::class, "store"]);

Route::post("/findIds", [\App\Http\Controllers\Api\FindIdController::class, "store"]);
Route::post("/findPasswords", [\App\Http\Controllers\Api\FindPasswordController::class, "store"]);

Route::get("/payMethods", [\App\Http\Controllers\Api\PayMethodController::class, "index"]);
Route::post("/orders/complete/webhook", [\App\Http\Controllers\Api\OrderController::class, "complete"]);
Route::post("/orders/complete", [\App\Http\Controllers\Api\OrderController::class, "complete"]);
Route::get("/orders/complete/mobile", [\App\Http\Controllers\Api\OrderController::class, "complete"]);
Route::get("/reviews", [\App\Http\Controllers\Api\ReviewController::class, "index"]);



Route::get("/events", [\App\Http\Controllers\Api\EventController::class, "index"]);
Route::get("/events/{event}", [\App\Http\Controllers\Api\EventController::class, "show"]);

Route::get("/faqs", [\App\Http\Controllers\Api\FaqController::class, "index"]);

Route::post("/visits", [\App\Http\Controllers\Api\VisitController::class, "store"]);

Route::get("/notices", [\App\Http\Controllers\Api\NoticeController::class, "index"]);
Route::get("/notices/{notice}", [\App\Http\Controllers\Api\NoticeController::class, "show"]);


Route::get("/categories", [\App\Http\Controllers\Api\CategoryController::class, "index"]);
// Route::get("/recommendCategories", [\App\Http\Controllers\Api\RecommendCategoryController::class, "index"]);

Route::get("/packages/canOrder", [\App\Http\Controllers\Api\PackageController::class, "canOrder"]);
Route::get("/packages/current", [\App\Http\Controllers\Api\PackageController::class, "current"]);
Route::get("/packages/{package}", [\App\Http\Controllers\Api\PackageController::class, "show"]);

Route::get("/banners", [\App\Http\Controllers\Api\BannerController::class, "index"]);
Route::get("/pops", [\App\Http\Controllers\Api\PopController::class, "index"]);
Route::get("/counts", [\App\Http\Controllers\Api\CountController::class, "index"]);
Route::get("/cities", [\App\Http\Controllers\Api\CityController::class, "index"]);
Route::get("/tags", [\App\Http\Controllers\Api\TagController::class, "index"]);
Route::get("/recipes", [\App\Http\Controllers\Api\RecipeController::class, "index"]);
Route::get("/recipes/{recipe}", [\App\Http\Controllers\Api\RecipeController::class, "show"]);
Route::get("/farmStories", [\App\Http\Controllers\Api\FarmStoryController::class, "index"]);
Route::get("/farmStories/{farmStory}", [\App\Http\Controllers\Api\FarmStoryController::class, "show"]);
Route::get("/products", [\App\Http\Controllers\Api\ProductController::class, "index"]);
Route::get("/products/{product}", [\App\Http\Controllers\Api\ProductController::class, "show"]);
Route::get("/projects", [\App\Http\Controllers\Api\ProjectController::class, "index"]);
Route::get("/couponGroups", [\App\Http\Controllers\Api\CouponGroupController::class, "index"]);


Route::post('/verifyNumbers', [\App\Http\Controllers\Api\VerifyNumberController::class, "store"]);
Route::patch('/verifyNumbers', [\App\Http\Controllers\Api\VerifyNumberController::class, "update"]);

Route::patch("/users/clearPassword", [\App\Http\Controllers\Api\UserController::class, "clearPassword"]);
Route::patch("/users/findId", [\App\Http\Controllers\Api\UserController::class, "findId"]);

Route::get("/vegetableStories", [\App\Http\Controllers\Api\VegetableStoryController::class, "index"]);
Route::get("/vegetableStories/{vegetableStory}", [\App\Http\Controllers\Api\VegetableStoryController::class, "show"]);
Route::get("/comments", [\App\Http\Controllers\Api\CommentController::class, 'index']);
Route::get('/reportCategories', [\App\Http\Controllers\Api\ReportCategoryController::class, "index"]);

Route::get('/faqCategories', [\App\Http\Controllers\Api\FaqCategoryController::class, "index"]);
Route::get('/faqs', [\App\Http\Controllers\Api\FaqController::class, "index"]);
Route::get('/grades', [\App\Http\Controllers\Api\GradeController::class, "index"]);
Route::get("/presetProducts/delivery/{presetProduct}", [\App\Http\Controllers\Api\PresetProductController::class, "updateDelivery"]);

Route::middleware(['auth'])->group(function () {
    Route::post('/reports', [\App\Http\Controllers\Api\ReportController::class, "store"]);


    Route::get("/packageSettings", [\App\Http\Controllers\Api\PackageSettingController::class, "index"]);
    Route::get("/packageSetting", [\App\Http\Controllers\Api\PackageSettingController::class, "show"]);
    Route::post("/packageSettings", [\App\Http\Controllers\Api\PackageSettingController::class, "store"]);
    Route::patch("/packageSettings/{packageSetting}", [\App\Http\Controllers\Api\PackageSettingController::class, "update"]);
    Route::patch("/packageSettings/unlikeMaterials/{packageSetting}", [\App\Http\Controllers\Api\PackageSettingController::class, "updateUnlikeMaterials"]);

    Route::post("/comments", [\App\Http\Controllers\Api\CommentController::class, 'store']);
    Route::patch("/comments/{comment}", [\App\Http\Controllers\Api\CommentController::class, 'update']);
    Route::delete("/comments/{comment}", [\App\Http\Controllers\Api\CommentController::class, 'destroy']);

    Route::post("/vegetableStories", [\App\Http\Controllers\Api\VegetableStoryController::class, 'store']);
    Route::delete("/vegetableStories/{vegetableStory}", [\App\Http\Controllers\Api\VegetableStoryController::class, 'destroy']);
    Route::patch("/vegetableStories/{vegetableStory}", [\App\Http\Controllers\Api\VegetableStoryController::class, 'update']);

    Route::get("/cards", [\App\Http\Controllers\Api\CardController::class, 'index']);
    Route::post("/cards", [\App\Http\Controllers\Api\CardController::class, 'store']);

    Route::get("/carts", [\App\Http\Controllers\Api\CartController::class, 'index']);
    Route::post("/carts", [\App\Http\Controllers\Api\CartController::class, 'store']);
    Route::delete("/carts", [\App\Http\Controllers\Api\CartController::class, 'destroy']);
    Route::patch("/carts", [\App\Http\Controllers\Api\CartController::class, 'update']);

    Route::get('/deliveries', [\App\Http\Controllers\Api\DeliveryController::class, "index"]);
    Route::get('/deliveries/{delivery}', [\App\Http\Controllers\Api\DeliveryController::class, "show"]);
    Route::post('/deliveries', [\App\Http\Controllers\Api\DeliveryController::class, "store"]);
    Route::patch('/deliveries/{delivery}', [\App\Http\Controllers\Api\DeliveryController::class, "update"]);
    Route::delete('/deliveries/{delivery}', [\App\Http\Controllers\Api\DeliveryController::class, "destroy"]);

    Route::get("/user", [\App\Http\Controllers\Api\UserController::class, 'show']);

    Route::patch("/users", [\App\Http\Controllers\Api\UserController::class, "update"]);
    Route::patch("/users/alwaysUseCouponForPackage", [\App\Http\Controllers\Api\UserController::class, "updateAlwaysUseCouponForPackage"]);
    Route::patch("/users/alwaysUsePointForPackage", [\App\Http\Controllers\Api\UserController::class, "updateAlwaysUsePointForPackage"]);
    Route::patch("/users/codeRecommend", [\App\Http\Controllers\Api\UserController::class, "updateCodeRecommend"]);
    Route::patch("/users/password", [\App\Http\Controllers\Api\UserController::class, "updatePassword"]);

    Route::delete("/users", [\App\Http\Controllers\Api\UserController::class, "destroy"]);
    Route::get("/logout", [\App\Http\Controllers\Api\UserController::class, "logout"]);
    Route::post("/logout", [\App\Http\Controllers\Api\UserController::class, "logout"]);

    Route::resource("/qnas", QnaController::class);

    Route::get("/orders",[\App\Http\Controllers\Api\OrderController::class, 'index']);
    Route::post("/orders",[\App\Http\Controllers\Api\OrderController::class, 'store']);
    Route::patch("/orders/delivery/{order}",[\App\Http\Controllers\Api\OrderController::class, 'updateDelivery']);
    Route::get("/orders/calculatePriceDelivery/{order}",[\App\Http\Controllers\Api\OrderController::class, 'calculatePriceDelivery']);


    Route::get("/refunds",[\App\Http\Controllers\Api\RefundController::class, 'index']);
    Route::post("/refunds",[\App\Http\Controllers\Api\RefundController::class, 'store']);


    Route::get("/reviews/{review}", [\App\Http\Controllers\Api\ReviewController::class, "show"]);
    Route::post("/reviews", [\App\Http\Controllers\Api\ReviewController::class, "store"]);
    Route::patch("/reviews/{review}", [\App\Http\Controllers\Api\ReviewController::class, "update"]);
    Route::delete("/reviews/{review}", [\App\Http\Controllers\Api\ReviewController::class, "destroy"]);

    Route::post("/presets", [\App\Http\Controllers\Api\PresetController::class, "store"]);
    Route::patch("/presets/coupon/{preset}", [\App\Http\Controllers\Api\PresetController::class, "updateCoupon"]);
    Route::patch("/presets/{preset}", [\App\Http\Controllers\Api\PresetController::class, "update"]);

    Route::get("/presetProducts", [\App\Http\Controllers\Api\PresetProductController::class, "index"]);
    Route::get("/presetProducts/currentPackage", [\App\Http\Controllers\Api\PresetProductController::class, "currentPackage"]);
    Route::get("/presetProducts/{presetProduct}", [\App\Http\Controllers\Api\PresetProductController::class, "show"]);
    Route::patch("/presetProducts/fast/{presetProduct}", [\App\Http\Controllers\Api\PresetProductController::class, "fast"]);
    Route::patch("/presetProducts/late/{presetProduct}", [\App\Http\Controllers\Api\PresetProductController::class, "late"]);
    Route::patch("/presetProducts/recoverCancel/{presetProduct}", [\App\Http\Controllers\Api\PresetProductController::class, "recoverCancel"]);
    Route::patch("/presetProducts/materials/{presetProduct}", [\App\Http\Controllers\Api\PresetProductController::class, "updateMaterials"]);
    Route::patch("/presetProducts/requestCancel/{presetProduct}",[\App\Http\Controllers\Api\PresetProductController::class, 'requestCancel']);
    Route::patch("/presetProducts/cancel/{presetProduct}",[\App\Http\Controllers\Api\PresetProductController::class, 'cancel']);
    Route::patch("/presetProducts/confirm/{presetProduct}", [\App\Http\Controllers\Api\PresetProductController::class, "confirm"]);

    Route::get('/pointHistories', [\App\Http\Controllers\Api\PointHistoryController::class, "index"]);
    Route::get('/couponHistories', [\App\Http\Controllers\Api\CouponHistoryController::class, "index"]);

    Route::get("/qnaCategories", [\App\Http\Controllers\Api\QnaCategoryController::class, "index"]);
    Route::get("/qnas", [\App\Http\Controllers\Api\QnaController::class, "index"]);

    Route::get('/coupons', [\App\Http\Controllers\Api\CouponController::class, "index"]);
    Route::post("/coupons", [\App\Http\Controllers\Api\CouponController::class, "store"]);

    Route::post("/likes", [\App\Http\Controllers\Api\LikeController::class, "store"]);
    Route::get("/bookmarks", [\App\Http\Controllers\Api\BookmarkController::class, "index"]);
    Route::post("/bookmarks", [\App\Http\Controllers\Api\BookmarkController::class, "store"]);

    Route::post('/reports', [\App\Http\Controllers\Api\ReportController::class, "store"]);

    Route::patch("/orders/{order}",[\App\Http\Controllers\Api\OrderController::class, 'update']);
    Route::get("/orders/{order}",[\App\Http\Controllers\Api\OrderController::class, 'show']);
    Route::post("/orders",[\App\Http\Controllers\Api\OrderController::class, 'store']);
});
