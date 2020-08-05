<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessMember;
use App\Models\Coupon;
use App\Models\Integration;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BusinessController extends BaseController
{
    function addBusiness(Request $request){
        $request->validate([
            'name' => 'string|required',
            'email' => 'email|required',
            'phone' => 'string|required',
            'address' => 'string|required',
        ]);

        if(!$request->get('ein')){
            $request->validate([
                'contact_person' => 'string|required',
                'contact_email' => 'email|required',
                'contact_phone' => 'string|required',
            ]);
        }

        $data = $request->all();
        $data['is_registered'] = $request->get('is_registered') == 'yes' ? 1 : 0;
        if($request->hasFile('images')){
            $image = $request->file('images')[0];
            $fileName = $image->getClientOriginalName();
            $path = 'business/' . time();
            Storage::disk('s3')->putFileAs($path, $image, $fileName);
            $data['logo'] = Storage::disk('s3')->url($path.'/'.$fileName);
        }

        if($request->hasFile('banners')){
            $image = $request->file('banners')[0];
            $fileName = $image->getClientOriginalName();
            $path = 'business/banners/' . time();
            Storage::disk('s3')->putFileAs($path, $image, $fileName);
            $data['banner'] = Storage::disk('s3')->url($path.'/'.$fileName);
        }

        if($request->get('business_id')){
            $business = Business::find($request->get('business_id'));
            $business->fill($data);
            $business->save();
        }
        else{
            $business = Business::create($data);
        }

        if(!$business){
            throw new BadRequestHttpException('Something went wrong, try again later');
        }

        if(!$request->get('business_id')) {
            BusinessMember::create([
                'user_id' => auth()->id(),
                'business_id' => $business->id,
                'is_joined' => 1,
                'is_suspended' => 0,
                'role' => 'admin'
            ]);
        }

        $message = 'Business account has been '  . ($request->has('business_id') ? 'updated' : 'created');
        return $this->getResponse($business, $message);
    }

    function update(Request $request, $businessId) {
        $business = Business::find($businessId);

        if(!$business){
            throw new BadRequestHttpException('Something went wrong, try again later');
        }

        $request->validate([
            'name' => 'string|required',
            'email' => 'email|required',
            'phone' => 'string|required',
            'address' => 'string|required',
        ]);

        if(!$request->get('ein')){
            $request->validate([
                'contact_person' => 'string|required',
                'contact_email' => 'email|required',
                'contact_phone' => 'string|required',
            ]);
        }

        $data = $request->all();

        if($request->hasFile('images')){
            $image = $request->file('images')[0];
            $fileName = $image->getClientOriginalName();
            $path = 'business/' . time();
            Storage::disk('s3')->putFileAs($path, $image, $fileName);
            $data['logo'] = Storage::disk('s3')->url($path.'/'.$fileName);
        }

        if($request->hasFile('banners')){
            $image = $request->file('banners')[0];
            $fileName = $image->getClientOriginalName();
            $path = 'business/banners/' . time();
            Storage::disk('s3')->putFileAs($path, $image, $fileName);
            $data['banner'] = Storage::disk('s3')->url($path.'/'.$fileName);
        }

        $business->fill($data);
        $business->save();

        if(!$business){
            throw new BadRequestHttpException('Something went wrong, try again later');
        }

        return $this->getResponse($business, 'Business account has been updated');
    }

    function suspendBusiness($businessId) {
        $business = Business::find($businessId);

        if(!$business){
            throw new BadRequestHttpException('Something went wrong, try again later');
        }

        $business->is_suspended = 1;
        $business->save();

        return $this->getResponse($business, 'Business has been suspended');
    }

    function restoreBusiness($businessId) {
        $business = Business::find($businessId);

        if(!$business){
            throw new BadRequestHttpException('Something went wrong, try again later');
        }

        $business->is_suspended = 0;
        $business->save();

        return $this->getResponse($business, 'Business has been restored');
    }

    function deleteBusiness($businessId) {
        $business = Business::find($businessId);

        if(!$business){
            throw new BadRequestHttpException('Something went wrong, try again later');
        }

        Post::where('business_id', $businessId)->delete();
        Coupon::where('business_id', $businessId)->delete();
        BusinessMember::where('business_id', $businessId)->delete();

        $business->delete();


        return $this->getResponse(true, 'Business has been deleted');
    }

    function addIntegration(Request $request) {
        $request->validate([
            'name' => 'string|required',
            'app_name' => 'string|required',
            'key' => 'string|required',
        ]);

        $data = $request->all();
        $data['business_id'] = auth()->user()->active_business ? auth()->user()->active_business->id : '';
        Log::info('data', [$data]);
        if(!$data['business_id']){
            throw new BadRequestHttpException('Something went wrong, try again later');
        }
        $integration = Integration::create($data);

        return $this->getResponse($integration, 'Integration has been added');
    }

    function updateIntegration($id, Request $request) {
        $request->validate([
            'name' => 'string|required',
            'app_name' => 'string|required',
            'key' => 'string|required',
        ]);


        $data = $request->all();
        Log::info('data', [$data]);
        $integration = Integration::find($id);

        if(!$integration){
            throw new BadRequestHttpException('Something went wrong, try again later');
        }

        $integration->fill($data);
        $integration->save();

        return $this->getResponse($integration, 'Integration has been updated');
    }

    function removeIntegration($id) {
        $integration = Integration::find($id);
        if(!$integration){
            throw new BadRequestHttpException('Something went wrong, try again later');
        }

        $integration->delete();

        return $this->getResponse($integration, 'Integration has been deleted');
    }
}
