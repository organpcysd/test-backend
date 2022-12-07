<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::get('storage_link', function () {
    Artisan::call('storage:link');
});
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginform']);
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);
Auth::routes();

Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('home');

Route::group(
    ['prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
], function()
{
    Route::group(['middleware' => ['is_active']], function () {
        Route::prefix('admin')->group(function () {

            Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
            Route::resource('/dashboard', \App\Http\Controllers\Admin\DashboardController::class);

            Route::group(['middleware' => ['can:banner']], function () {
                Route::resource('/banner', App\Http\Controllers\Admin\BannerController::class);
                Route::get('/banner/publish/{id}', [\App\Http\Controllers\Admin\BannerController::class, 'publish'])->name('banner.publish');
                Route::get('/banner/sort/{id}', [App\Http\Controllers\Admin\BannerController::class, 'sort'])->name('banner.sort');
            });

            Route::group(['middleware' => ['can:news']], function () {
                Route::resource('/news', App\Http\Controllers\Admin\NewsController::class);
                Route::get('/news/publish/{id}', [\App\Http\Controllers\Admin\NewsController::class, 'publish'])->name('news.publish');
                Route::get('/news/sort/{id}', [App\Http\Controllers\Admin\NewsController::class, 'sort'])->name('news.sort');
            });

            Route::group(['middleware' => ['can:promotion']], function () {
                Route::resource('/promotion', App\Http\Controllers\Admin\PromotionController::class);
                Route::get('/promotion/publish/{id}', [\App\Http\Controllers\Admin\PromotionController::class, 'publish'])->name('promotion.publish');
                Route::get('/promotion/sort/{id}', [App\Http\Controllers\Admin\PromotionController::class, 'sort'])->name('promotion.sort');
            });

            Route::group(['middleware' => ['can:service']], function () {
                Route::resource('/service', App\Http\Controllers\Admin\ServiceController::class);
                Route::get('/service/publish/{id}', [\App\Http\Controllers\Admin\ServiceController::class, 'publish'])->name('service.publish');
                Route::get('/service/sort/{id}', [App\Http\Controllers\Admin\ServiceController::class, 'sort'])->name('service.sort');
            });

            Route::group(['middleware' => ['can:product']], function () {
                Route::resource('/product', App\Http\Controllers\Admin\ProductController::class);
                Route::get('/product/publish/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'publish'])->name('product.publish');
                Route::get('/product/sort/{id}', [App\Http\Controllers\Admin\ProductController::class, 'sort'])->name('product.sort');
                Route::get('/product/promotion/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'promotion'])->name('product.promotion');
                Route::get('/product/bestsell/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'bestsell'])->name('product.bestsell');
                Route::get('/product/recommended/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'recommended'])->name('product.recommended');

                Route::get('/product/getSubcategory/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'getSubcategory'])->name('product.getSubcategory');


                Route::resource('/category', App\Http\Controllers\Admin\ProductCategoryController::class);
                Route::get('/category/publish/{id}', [\App\Http\Controllers\Admin\ProductCategoryController::class, 'publish'])->name('category.publish');
                Route::get('/category/sort/{id}', [App\Http\Controllers\Admin\ProductCategoryController::class, 'sort'])->name('category.sort');

                Route::resource('/subcategory', App\Http\Controllers\Admin\SubProductCategoryController::class);
                Route::get('/subcategory/getCategory/{id}', [\App\Http\Controllers\Admin\SubProductCategoryController::class, 'getCategory'])->name('subcategory.getCategory');
                Route::get('/subcategory/publish/{id}', [\App\Http\Controllers\Admin\SubProductCategoryController::class, 'publish'])->name('subcategory.publish');
                Route::get('/subcategory/sort/{id}', [App\Http\Controllers\Admin\SubProductCategoryController::class, 'sort'])->name('subcategory.sort');
            });

            Route::group(['middleware' => ['can:faq']], function () {
                Route::resource('/faqcategory', App\Http\Controllers\Admin\FaqCategoryController::class);
                Route::get('/faqcategory/publish/{id}', [\App\Http\Controllers\Admin\FaqCategoryController::class, 'publish'])->name('faqcategory.publish');
                Route::get('/faqcategory/sort/{id}', [App\Http\Controllers\Admin\FaqCategoryController::class, 'sort'])->name('faqcategory.sort');

                Route::resource('/faq', App\Http\Controllers\Admin\FaqController::class);
                Route::get('/faq/getFaqcategory/{id}', [\App\Http\Controllers\Admin\FaqController::class, 'getfaqcategory'])->name('faq.getFaqcategory');
                Route::get('/faq/publish/{id}', [\App\Http\Controllers\Admin\FaqController::class, 'publish'])->name('faq.publish');
                Route::get('/faq/sort/{id}', [App\Http\Controllers\Admin\FaqController::class, 'sort'])->name('faq.sort');
            });

            Route::group(['middleware' => ['can:settings']], function () {
                Route::resource('/settings', App\Http\Controllers\Admin\SettingController::class);
            });

            Route::group(['middleware' => ['can:website']], function () {
                Route::resource('/website', App\Http\Controllers\Admin\WebsiteController::class);
            });

            Route::group(['middleware' => ['can:user']], function () {
                Route::resource('/user', App\Http\Controllers\Admin\UserController::class);
                Route::get('/user/publish/{id}', [\App\Http\Controllers\Admin\UserController::class, 'publish'])->name('user.publish');
            });

            Route::group(['middleware' => ['can:role']], function(){
                Route::resource('/role', App\Http\Controllers\Admin\RoleController::class);
            });

            Route::group(['middleware' => ['can:permission']], function(){
                Route::resource('/permission', App\Http\Controllers\Admin\PermissionController::class);
            });

            //Dropzone
            Route::post('/dropzone/upload', [App\Http\Controllers\Admin\DropzoneController::class, 'uploadimage'])->name('dropzone.upload');
            Route::post('/dropzone/delete', [App\Http\Controllers\Admin\DropzoneController::class, 'deleteupload'])->name('dropzone.delete');
        });
    });
});
