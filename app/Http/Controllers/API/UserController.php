<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\BusinessMember;
use App\Models\Coupon;
use App\Models\Post;
use App\Models\User;
use App\Notifications\BusinessMemberNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends BaseController
{
    function checkUserName(Request $request) {
        $count = User::where('username', $request->get('username'))->count();
        if($count > 0){
            throw new BadRequestHttpException('This username is not available');
        }

        return $this->getResponse($count, 'Username available');
    }

    function updateProfile(Request $request, $userId){
        $user = User::find($userId);
        if(!$user){
            throw  new NotFoundHttpException('User not found');
        }

//        $request->validate([
//            'first_name' => 'string|required',
//            'last_name' => 'string|required',
//            'email' => 'email|required',
//        ]);

        $data = $request->all();

        if($request->has('image')){
            $base64 = $request->get('image');

            $image = str_replace('data:image/jpeg;base64,', '', $base64);
            $image = str_replace(' ', '+', $image);

            $path = 'users/' . Str::random(). '.jpg';
            Storage::disk('s3')->put($path, base64_decode($image), 'public');
            $data['avatar'] = Storage::disk('s3')->url($path);
        }

        if($request->hasFile('images')){
            $image = $request->file('images')[0];
            $fileName = $image->getClientOriginalName();
            $path = 'users/' . time();
            Storage::disk('s3')->putFileAs($path, $image, $fileName);
            $data['avatar'] = Storage::disk('s3')->url($path.'/'.$fileName);
        }

        $user->fill($data);
        $user->save();

        return $this->getResponse($user, 'User profle has been updated');
    }

    function unlinkSocialAccounts(Request $request, $userId) {
        $user = User::find($userId);
        if(!$user){
            throw  new NotFoundHttpException('User not found');
        }

        $account = $request->get('account');
        if($account == 'twitter'){
            $user->twitter_id = '';
        }
        else if($account == 'facebook'){
            $user->facebook_id = '';
        }

        $user->save();

        return $this->getResponse($user, 'Account has been unlinked');
    }

    function inviteUser(Request $request) {
        $email = $request->get('email');
        if(!$email) {
            throw  new BadRequestHttpException('Email id is required');
        }

        $user = User::where('email', $email)->first();
        if(!$user){
            throw  new NotFoundHttpException('User not found');
        }

        $businessId = session()->get('business_id');
        if(!$businessId){
            throw  new BadRequestHttpException('Something went wrong, try again later');
        }

        $existingMember = BusinessMember::where('business_id', $businessId)->where('user_id', $user->id)->first();

        $member = '';
        if(!$existingMember){
            $member = BusinessMember::create([
                'user_id' => $user->id,
                'business_id' => $businessId,
                'is_joined' => 0,
                'role' => 'marketing'
            ]);
        }


        if($existingMember  && $existingMember->is_joined){
            throw  new BadRequestHttpException('You\'re already member of the organization');
        }

        $notifiableUser = new User();
        $notifiableUser->email = $email;
        $notifiableUser->notify(new BusinessMemberNotification($user->id, $businessId));


        $members = BusinessMember::where('business_id', $businessId)->with(['user'])->get();
        return $this->getResponse($members, 'User has been invited');
    }

    function joinBusiness(Request $request) {

    }

    function updateRole(Request $request, $userId) {
        $business = auth()->user()->active_business ;
        $businessId = $business ? $business->id : '';
        $role = $request->get('role');

        if(auth()->id() == $userId){
            throw  new BadRequestHttpException('You can\'t change your own role');
        }

        $businessMember = BusinessMember::where('business_id', $businessId)
            ->where('user_id', $userId)
            ->first();

        if($businessMember){
            $businessMember->role = $role;
            $businessMember->save();
        }


        $members = BusinessMember::where('business_id', $businessId)->with(['user'])->get();

        return $this->getResponse($members, 'Role has been updated');

    }

    function deleteUser($userId) {
        $user = User::find($userId);

        if(!$user) {
            throw  new BadRequestHttpException('User not found, try again later');
        }

        //Delete Posts
        Post::where('user_id', $userId)->delete();
        //Delete Coupons
        Coupon::where('user_id', $userId)->delete();

        BusinessMember::where('user_id', $userId)->delete();
        $user->delete();

        return $this->getResponse(true, 'User has been deleted');
    }

    function removeMember($id) {        
        $businessMember = BusinessMember::find($id);         
        $businessId =  $businessMember->business_id;         
        if($businessMember->role == 'admin'){
            throw  new BadRequestHttpException('You can not delete an admin account.If you want to delete an admin account please email Vocit at justintheceo@vocit.io');
        }        
        $businessMember->delete();
        $members = BusinessMember::where('business_id', $businessId)->with(['user'])->get();
        return $this->getResponse($members, 'User has been removed');
    }

    function me() {
        $data = auth()->user();
        return $this->getResponse($data, 'User profile found');
    }
}
