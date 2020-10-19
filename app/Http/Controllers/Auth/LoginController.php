<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        session()->forget('active_business');
        session()->flush();
        return redirect('/');
    }

    public function authenticated(Request $request, $user)
    {
        $userId = $user->id;
        Auth::loginUsingId($userId, 1);
        return redirect(url('/'));
    }

    /*
    * Redirect the user to the Facebook authentication page.
    *
    * @return \Illuminate\Http\Response
    */
    public function redirectToFacebookProvider()
    {
        return Socialite::driver('facebook')->scopes([
            "email", "pages_show_list"
        ])->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return void
     */
    public function handleProviderFacebookCallback()
    {
        $auth_user = Socialite::driver('facebook')->user();

        $name = explode(' ', $auth_user->name);

        $user = User::updateOrCreate(
            [
                'email' => $auth_user->email
            ],
            [
                'api_token'     => $auth_user->token,
                'first_name'    => $name[0],
                'last_name'     => $name[1],
                'username'      => $auth_user->email
            ]
        );

        Auth::login($user, true);
        return redirect()->to($this->redirectTo); // Redirect to a secure page
    }
}
