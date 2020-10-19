<?php

use App\Http\Controllers\FrontController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\SocialAuthController;
use App\Http\Controllers\Web\SubscriptionController;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Srmklive\PayPal\Facades\PayPal;
use Srmklive\PayPal\Services\ExpressCheckout;

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

Route::get('/login/facebook', 'Auth\LoginController@redirectToFacebookProvider');

Route::get('/social/facebook/callback', 'Auth\LoginController@handleProviderFacebookCallback');

Route::group(['middleware' => [
    'auth'
]], function(){
    Route::get('/user', 'GraphController@retrieveUserProfile');
    Route::post('/user', 'GraphController@publishToProfile');

});

Route::group(['namespace' => 'web', 'middleware' => ['auth', 'isSuperadmin']], function (){
    Route::get('/admin', [
        AdminController::class,
        'index'
    ])->name('admin.home');
    Route::get('/admin/users', [
        AdminController::class,
        'users'
    ])->name('admin.users');
    Route::get('/admin/posts', [
        AdminController::class,
        'posts'
    ])->name('admin.posts');
    Route::get('/admin/comments', [
        AdminController::class,
        'comments'
    ])->name('admin.comments');

    Route::get('/admin/businesses', [
        AdminController::class,
        'businesses'
    ])->name('admin.companies');
    Route::get('/admin/payments', [
        AdminController::class,
        'payments'
    ])->name('admin.payments');
});

Route::view('sandbox/login', 'sandbox-auth.login');
Route::post('sandbox/login', [
    FrontController::class,
    'sandboxLogin'
]);
Route::post('sandbox/signup', [
    FrontController::class,
    'sandboxSignup'
]);
Route::view('terms', 'terms');
Route::view('privacy', 'privacy');
Route::view('help', 'help');
Route::view('about', 'welcome');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('hubspot/contact', function() {
//    $hubSpot = SevenShores\Hubspot\Factory::create(env('HUBSPOT_API_KEY'));
    $arr = array(
        'properties' => array(
            array(
                'property' => 'email',
                'value' => 'apitest@hubspot.com'
            ),
            array(
                'property' => 'firstname',
                'value' => 'hubspot'
            ),
            array(
                'property' => 'lastname',
                'value' => 'user'
            ),
            array(
                'property' => 'phone',
                'value' => '555-1212'
            )
        )
    );
    $post_json = json_encode($arr);
    $endpoint = 'https://api.hubapi.com/contacts/v1/contact?hapikey=' . env('HUBSPOT_API_KEY');
    $ch = @curl_init();
    @curl_setopt($ch, CURLOPT_POST, true);
    @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
    @curl_setopt($ch, CURLOPT_URL, $endpoint);
    @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = @curl_exec($ch);
    $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_errors = curl_error($ch);
    @curl_close($ch);
});

$domain = 'sandbox.' . env('APP_DOMAIN');
Route::group([], function(){
    Auth::routes();

    Route::get('test', function(){
        $post = \App\Models\Post::find('4');
        $post_json = json_encode($post);
//        $endpoint = 'https://hooks.zapier.com/hooks/catch/7991217/okb3576';
        $endpoint = 'https://hooks.zapier.com/hooks/catch/7991217/ok431qj/';
        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        @curl_setopt($ch, CURLOPT_URL, $endpoint);
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = @curl_exec($ch);
        $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errors = curl_error($ch);
        dd($response);
        @curl_close($ch);
    });

    Route::view('setpassword','auth.setpassword');
    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

    Route::get('/', [
        FrontController::class,
        'getPosts'
    ]);
    Route::view('terms', 'terms');
    Route::view('privacy', 'privacy');
    Route::view('help', 'help');
    Route::view('about', 'about-v2')->name('about');
    Route::view('business/join', 'create-business');
    Route::get('business/invitation', [
        FrontController::class,
        'joinBusiness'
    ]);
    Route::post('business/invitation', [
        FrontController::class,
        'acceptInvitation'
    ]);
    Route::get('/posts/{type}', [
        FrontController::class,
        'getPosts'
    ]);
    Route::get('/post/{postSlug}', [
        FrontController::class,
        'getPost'
    ])->name('viewPost');
    Route::get('/exchange/{exchangeId}', [
        FrontController::class,
        'getExchange'
    ])->name('viewPost');
//Route::get('impersonate/account', function(\Illuminate\Http\Request $request){
//    $userId = $request->get('user');
//    Auth::loginUsingId($userId);
//    return redirect('/');
//});

    Route::get('account/suspended', function(){
        return view('permission-partial');
    });
    Route::get('login/{providerName}', [
        SocialAuthController::class,
        'handleSocialAuth'
    ]);
    Route::get('register/{providerName}', [
        SocialAuthController::class,
        'handleSocialAuth'
    ]);
    Route::get('social/{providerName}/callback', [
        SocialAuthController::class,
        'callback'
    ]);
    Route::post('social/{providerName}/callback', [
        SocialAuthController::class,
        'callback'
    ]);

    Route::group(['namespace' => 'web', 'middleware' => ['auth']], function(){

        Route::get('share/token/facebook', [
            FrontController::class,
            'getAccesstoken'
        ]);
        Route::get('share/social/{providerName}', [
            FrontController::class,
            'socialShare'
        ]);

        Route::get('/payment', [
            FrontController::class,
            'payment'
        ]);
        Route::post('/payment', [
            SubscriptionController::class,
            'makePayment'
        ]);
        Route::get('/payment/process', [
            SubscriptionController::class,
            'paymentSuccess'
        ]);
        Route::get('/payment/cancel', [
            SubscriptionController::class,
            'paymentCancel'
        ]);
        Route::get('profile', [
            FrontController::class,
            'profile'
        ]);
        Route::get('wallet', [
            FrontController::class,
            'userWallet'
        ]);
        Route::get('{businessname}/posts', [
            FrontController::class,
            'businessWallet'
        ])->name('businessWallet');
        Route::get('{businessname}/profile', [
            FrontController::class,
            'businessProfile'
        ]);
        Route::get('{businessname}/switch', [
            FrontController::class,
            'switchBusiness'
        ]);
        Route::get('posts/{postId}/medias', [
            FrontController::class,
            'downloadMedia'
        ]);
        Route::get('coupons/{couponId}/pdf', [
            FrontController::class,
            'downloadPdf'
        ]);

    });
    Route::get('/{businessName}', [
        FrontController::class,
        'portfolio'
    ]);
});

//Route::view('/', 'welcome');



