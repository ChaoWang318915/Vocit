<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    function handleSocialAuth($provider) {
        $reponse = [];
        if($provider == 'google'){
            return Socialite::driver('Google')->stateless()->redirect();
        }
        else if($provider == 'facebook'){
            return Socialite::driver('facebook')->stateless()->redirect();
        }
        else if($provider == 'instagram'){
            return Socialite::driver('instagram')->stateless()->redirect();
        }
    }

    function callback($provider) {
        $user = '';
        if($provider == 'facebook') {
            $user = Socialite::driver('facebook')->stateless()->user();
        }
        else if($provider == 'instagram'){
            $user = Socialite::driver('instagram')->stateless()->user();
        }
        else if($provider == 'google') {
            $user = Socialite::driver('Google')->stateless()->user();
        }

        if ($user) {
            $email = $user->email;
            $name = $user->getName();

            $username = strtolower(Str::snake($user->getNickname() ? $user->getNickname() : $name));

            if(!$email && !$username){
                return redirect()->back()->withErrors(['error', 'User don\'t have valid email address or username']);
            }


            if(!$email){
                $currentUser = User::whereUsername($username)->first();
            }
            else{
                $currentUser = User::whereEmail($email)->first();
            }

            if (!$currentUser) {
                $usernameCount = User::where('username', $username)->count();
                if($usernameCount > 0){
                    $username = $username . rand(00, 99);
                }

                $data['email'] = $user->getEmail();
                $data['first_name'] = Arr::get(explode(' ', $name), 0);
                $data['last_name'] = Arr::get(explode(' ', $name), 1);
                $data['username'] = $username;

                $urlPrams = http_build_query($data);

                return redirect()->to(('setpassword?'. $urlPrams));

//                $currentUser = User::create($data);
            }
            else{
                Auth::loginUsingId($currentUser->id, 1);
                return redirect(url('/'));
            }

//            if ($currentUser) {
//                Auth::loginUsingId($currentUser->id, 1);
//                return redirect(url('/'));
//            } else {
//                return redirect()->back()->withErrors(['error', 'Something went wrong, try again later.']);
//            }
        }
    }
}
