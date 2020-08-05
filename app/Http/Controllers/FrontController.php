<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessMember;
use App\Models\Coupon;
use App\Models\Integration;
use App\Models\Post;
use App\Models\User;
use App\Service\GetPostsService;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use Spatie\MediaLibrary\MediaStream;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FrontController extends Controller
{
    protected $service;
    public function __construct(GetPostsService $getPosts)
    {
        $this->service = $getPosts;
    }

    function getPosts(Request $request) {
        $requestData = collect($request->all());
        $type = $request->segment(2);
        $requestData->put('type', $type);
        $data['posts'] = $this->service->getRequests($requestData);
        $data['pageParams'] = [
            'type' => $type
        ];

        return view('index', $data);
    }

    function getPost($postId) {
        $data['post'] = Post::find($postId);
        if($data['post']->is_request){
            $data['canShare'] = auth()->check() ? (auth()->id() == $data['post']->user_id ? true : false) : false;
        }
        else{
            if(auth()->user()->active_business && auth()->user()->active_business->id == $data['post']->business_id){
                $data['canShare'] = true;
            }
            else{
                $data['canShare'] = auth()->check() ? (auth()->id() == $data['post']->user_id ? true : false) : false;
            }

        }

        return view('post', $data);
    }

    function getExchange($exchangeId) {
        $data['post'] = Post::find($exchangeId);
        return view('view-exchange', $data);
    }


    function portfolio(Request $request) {
        $businessName = $request->segment(1);
        $data['business'] = Business::where('subdomain', $businessName)->first();
        if(!$data['business']){
            return redirect()->back();
        }

        $requestData = collect($request->all());
        $requestData->put('is_business', true);
        $requestData->put('business', $businessName);

        $posts = $this->service->getRequests($requestData);
        $data['posts'] = $posts;
        $data['pageParams'] = [
            'type' => 'business',
            'value' => $businessName
        ];

        if($data['business']->is_subscribed){
            return view('portfolio', $data);
        }
        else{
            return view('subscribe', $data);
        }

    }

    function businessProfile($businessName){
        $data['members'] = BusinessMember::whereHas('business', function($b) use($businessName){
            $b->where('subdomain', $businessName);
        })->with(['user'])->get();

        $userId = auth()->id();
        $data['businesses'] = Business::whereHas('members', function($m) use($userId) {
            $m->where('user_id', $userId)->where('is_joined', 1);
        })->get();

        $business = auth()->user()->active_business;

        $data['plan'] = '';
        $subscription = $business ? $business->subscription : '';

        if($subscription){
            $plans = Lang::get('plans');
            $data['plan']  = Arr::get($plans, $subscription->package);
        }

        $plans = Lang::get('plans');
        $data['plans'] = $plans;

        $data['has_subscription'] = $subscription ? true : false;
        $data['post_limit'] = $business->post_limit;
        $data['integrations'] = Integration::where('business_id', $business->id)->get();
        $data['apiKey'] = 'Bearer '.auth()->user()->api_token;
        return view('business', $data);
    }

    function profile() {
        $data['user'] = auth()->user();
        return view('profile', $data);
    }

    function businessWallet(Request $request, $businessName) {
        $userId = auth()->id();
        $postId = $request->get('edit');

        $data['post'] = [];
        if($postId) {
            $data['post'] = Post::find($postId);
        }

        $data['postLimit'] = auth()->user()->active_business ? auth()->user()->active_business->post_limit : 0;

        return view('business-wallet', $data);
    }

    function userWallet() {
        $userId = auth()->id();
        $data['coupons'] = Coupon::with(['business', 'post'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('wallet', $data);
    }

    function setting($businessName){
        $data['members'] = BusinessMember::whereHas('business', function($b) use($businessName){
            $b->where('subdomain', $businessName);
        })->with(['user'])->get();

        $data['subscription'] = '';

        return view('setting', $data);
    }

    function joinBusiness(Request $request) {

        $data['params'] = $request->all();
        $data['error'] = '';

        $email = $request->get('email');
        $data['email'] = $email;

        $isValidInvitation = BusinessMember::where('business_id', $request->get('bid'))->whereHas('user', function($u) use($email){
            $u->whereEmail($email);
        })->exists();

        if(!$isValidInvitation){
            $data['error'] = 'Invalid invitation';
        }

        return view('invitation', $data);
    }

    function acceptInvitation(Request $request){
        $email = $request->get('email');
        $member = BusinessMember::where('business_id', $request->get('bid'))->whereHas('user', function($u) use($email){
            $u->whereEmail($email);
        })->first();

        if(!$member){
            return redirect()->back()->withErrors(['error', 'User not found']);
        }

        $member->is_joined = 1;
        $member->save();

        return redirect()->to('/');
    }

    function downloadMedia($postId) {
        $post = Post::find($postId);
        if(!$post){
            die('Attachments not found');
        }

        if(auth()->user()->active_business->id != $post->business_id){
            die('You don\'t have enough permission to access this url');
        }

        $downloads = $post->getMedia('Posts');
        return MediaStream::create(($post->id.'.zip'))->addMedia($downloads);
    }

    function switchBusiness($businessName) {
        $business = Business::where('subdomain', $businessName)->first();
        if($business){
            $businessId = $business->id;
            session()->put('active_business', $businessId);
        }

        return redirect()->back();
    }

    function payment(Request $request) {
        $data['packageName'] = $request->get('package');
        $data['postId'] = $request->get('post');
        $data['plan'] = collect(Arr::get(Lang::get('plans'), $data['packageName']));

        return view('payment', $data);
    }

    function downloadPdf($couponId) {
        $coupon = Coupon::find($couponId);
        if(!$coupon){
            die('Coupon not found');
        }

        $business = $coupon->business;
        $post = $coupon->post;

        $code = $coupon->coupon;
        $image = asset('storage/coupons/'. $code .'.png');

        $mpdf = new mPDF(['tempDir' => storage_path().'/tmp']);

        $html = '<div style="text-align: center">';
        $html .= '<img height="50" src="'.$business->logo.'" alt="">';
        $html .= '<h1>'.$post->short_description.'</h1>';
        $html .= '<img src="'.$image.'" alt="">';
        $html .= '<p>'.$post->content.'</p>';
        $html .= '</div>';

        $mpdf->WriteHTML($html);
        return $mpdf->Output( ($code.'.pdf'), \Mpdf\Output\Destination::INLINE);
    }

    function sandboxLogin(Request $request){
        $email = $request->get('email');
        $user = User::whereEmail($email)->where('sandbox_user', 1)->first();
        if(!$user){
            return redirect()->back()->withInput()->withErrors(['error' => 'This email do not match our records.']);
        }

        Auth::loginUsingId($user->id, 1);
        return redirect()->to(env('APP_SANDBOX'));
    }

    function sandboxSignup(Request $request){
        $data = $request->all();
        $data['username'] = $request->get('first_name').$request->get('last_name');
        $data['sandbox_user'] = 1;
        $data['api_token'] = Str::uuid();

        $user = User::where('email', $request->get('email'))->first();
        if($user){
            $isSandboxUser = $user->user_sandbox;
            $message = $isSandboxUser ? "You're already a sandbox user, please login." : "You're already registered.";
            return redirect()->back()->withErrors(['error' => $message]);
        }

        $userNameExists = User::where('username', $data['username'])->exists();
        if($userNameExists){
            $data['username'] = $data['username'].rand(00,99);
        }

        $user = User::create($data);
        Auth::loginUsingId($user->id, 1);
        return redirect(env('APP_SANDBOX'));
    }

    function getAccessToken() {

    }

    function socialShare($providerName) {

    }
}
