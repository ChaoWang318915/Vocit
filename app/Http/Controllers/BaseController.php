<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;

class BaseController extends Controller
{
    function getResponse($responseBody, $message = '', $meta = []) {
        $statusCode = 200;
        if(\request()->getMethod() == 'POST'){
            $statusCode = '201';
        }

        $response = collect([
            'data' => $responseBody,
            'message' => $message
        ]);

        if($meta){
            $response->put('meta', $meta);
        }

        return response()->json($response, $statusCode);
    }

    function getMessage($event,  $name = ''){
        $message = '';
        if($event){
            $message = Lang::get('messages.'. $event);
        }

        if($message && $name){
            $message = str_replace(':name', $name, $message);
        }

        return $message;
    }

    function uploadImage($image) {
        $url = '';
        $path = '/storage/icons/';
        if($image){
            $image = Storage::disk('s3')->put($path, $image);
        }
    }
}
