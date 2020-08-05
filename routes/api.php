<?php

use App\Http\Controllers\API\BusinessController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\UserController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/posts', [
    PostController::class,
    'getPosts'
]);

Route::group(['namespace' => 'API', ], function(){
    Route::group(['middleware' => ['auth:api']], function() {
        Route::get('/me', [
            UserController::class,
            'me'
        ]);

        Route::post('/posts', [
            PostController::class,
            'create'
        ]);
        Route::post('/posts/draft', [
            PostController::class,
            'createDraftImagePost'
        ]);
        Route::put('/posts/{postId}', [
            PostController::class,
            'updatePost'
        ]);
        Route::post('exchange', [
            PostController::class,
            'exchangePost'
        ]);
        Route::get('/posts/{postId}', [
            PostController::class,
            'getPost'
        ]);
        Route::delete('/posts/{postId}', [
            PostController::class,
            'deletePost'
        ]);
        Route::post('business/join', [
            BusinessController::class,
            'addBusiness'
        ]);

        Route::post('business/invite', [
            UserController::class,
            'inviteUser'
        ]);

        Route::post('business/integrations', [
            BusinessController::class,
            'addIntegration'
        ]);
        Route::put('business/integrations/{integrationId}', [
            BusinessController::class,
            'updateIntegration'
        ]);
        Route::delete('business/integrations/{integrationId}', [
            BusinessController::class,
            'removeIntegration'
        ]);


        Route::get('business/posts', [
            PostController::class,
            'getPosts'
        ]);

        Route::post('business/{businessId}', [
            BusinessController::class,
            'update'
        ]);

        Route::put('business/{businessId}/suspend', [
            BusinessController::class,
            'suspendBusiness'
        ]);

        Route::put('business/{businessId}/restore', [
            BusinessController::class,
            'restoreBusiness'
        ]);

        Route::delete('business/{businessId}', [
            BusinessController::class,
            'deleteBusiness'
        ]);
        Route::delete('business/{businessId}', [
            BusinessController::class,
            'deleteBusiness'
        ]);

        Route::post('users/{userId}', [
            UserController::class,
            'updateProfile'
        ]);
        Route::delete('users/{userId}', [
            UserController::class,
            'deleteUser'
        ]);

        Route::post('users/{userId}/socialunlink', [
            UserController::class,
            'unlinkSocialAccounts'
        ]);
        Route::put('users/{userId}/role', [
            UserController::class,
            'updateRole'
        ]);

        Route::delete('users/{userId}/remove', [
            UserController::class,
            'removeMember'
        ]);

        Route::get('business/coupons', [
            PostController::class,
            'coupons'
        ]);
        Route::put('business/coupons/{couponId}', [
            PostController::class,
            'redeemCoupon'
        ]);

        Route::post('payment', [
           PaymentController::class,
            'makePayment'
        ]);

        Route::post('subscription/cancel', [
            PaymentController::class,
            'cancelSubscription'
        ]);
        Route::post('impressions', [
            PostController::class,
            'addImpression'
        ]);
        Route::post('posts/{postId}/share', [
            PostController::class,
            'sharePost'
        ]);

    });
});
