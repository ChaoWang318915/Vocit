<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Facades\PayPal;

class SubscriptionController extends Controller
{
    protected $provider;
    protected $customerId;
    public function __construct()
    {
        $this->provider = PayPal::setProvider('express_checkout');
    }

    function makePayment(Request $request) {
        $data = [];
        $package = $request->get('package');
        $plans = Lang::get('plans');
        $quantity = $request->get('quantity');
        $packageInfo = Arr::get($plans, $package);
        $business = auth()->user()->active_business;
        $uniqueId = time();

        if(!$packageInfo){
            return redirect()->back()->withErrors(['error' => 'Payment plan not found']);
        }

        $data['items'] = [
            [
                'name' => $package,
                'price' => Arr::get($packageInfo, 'amount'),
                'desc'  => "Payment for post ". $business->name. ' #'.$uniqueId,
                'qty' => $quantity
            ]
        ];

        $data['invoice_id'] = 'Invoice #'.$uniqueId;
        $data['invoice_description'] = 'Invoice #'.$uniqueId.' for '.$business->name;
        $data['return_url'] = url('/payment/process?quantity='. $request->get('quantity'). '&package=' . $package.'&uid='. $uniqueId);
        $data['cancel_url'] = url('/payment?quantity='. $request->get('quantity'). '&package=' . $package);

        $total = 0;
        foreach($data['items'] as $item) {
            $total += $item['price']*$item['qty'];
        }

        $data['total'] = $total;

        if($package == 'single' || $package == 'Single'){
            $response = $this->provider->setExpressCheckout($data);
        }
        else{
            $response = $this->provider->setExpressCheckout($data, true);
        }

        Log::info($response);

        if(!Arr::get($response, 'paypal_link')){
            return redirect()->back()->withErrors(['error' => 'Something went wrong, try again later.']);
        }

        if(Arr::get($response, 'paypal_link')){
            $link = Arr::get($response, 'paypal_link');
            return redirect($link);
        }
    }

    function paymentSuccess(Request $request) {

        $token = $request->get('token');
        $quantity = $request->get('quantity');
        $uniqueId = $request->get('uid');
        $package = $request->get('package');
        $PayerID = $request->get('PayerID');
        $business = auth()->user()->active_business;

        $plans = Lang::get('plans');
        $packageInfo = Arr::get($plans, $package);
        if(!$packageInfo){
            return redirect()->to('payment?quantity='.$quantity.'&package='. $package)->withErrors(['error' => 'Payment plan not found']);
        }

        Log::info($packageInfo);



        $data['items'] = [
            [
                'name' => $package,
                'price' => Arr::get($packageInfo, 'amount'),
                'desc'  => "Payment for post ". $business->name. ' #'.$uniqueId,
                'qty' => $quantity
            ]
        ];

        $data['invoice_id'] = 'Invoice #'.$uniqueId;
        $data['invoice_description'] = 'Invoice #'.$uniqueId.' for '.$business->name;
        $total = 0;
        foreach($data['items'] as $item) {
            $total += $item['price']*$item['qty'];
        }

        $data['total'] = $total;
        $profileId = '';
        $postIncrease = $quantity;

        $response = $this->provider->getExpressCheckoutDetails($token);
        Log::info($response);
        if($package == 'single' || $package == 'Single'){
            $this->provider->doExpressCheckoutPayment($data, $token, $PayerID);
            $transactionId = collect($response)->get('CORRELATIONID');
            $isSubscription = 0;
            $postIncrease = $quantity;
        }
        else{
            $this->provider->doExpressCheckoutPayment($data, $token, $PayerID);
            $amount = Arr::get($packageInfo, 'amount');
            $description = 'Invoice #'.$uniqueId.' for '.$business->name;
            $subscriptionInfo = $this->provider->createMonthlySubscription($token, $amount, $description);
            $profileId = collect($subscriptionInfo)->get('PROFILEID');

            if(!$profileId){
                return redirect()->to('payment?quantity='.$quantity.'&package='. $package)->withErrors(['error' => 'Something went wrong, try again later.']);
            }

            $isSubscription = 1;
            $transactionId = collect($response)->get('CORRELATIONID');
            $postIncrease = 3;
        }


        $responseStatus = collect($response)->get('ACK');

        Log::info($responseStatus);
        Log::info($transactionId);


        $isSuccess = false;
        if($transactionId && $responseStatus == 'Success'){
            $payment = Payment::create([
                'business_id' => $business->id,
                'amount' => Arr::get($packageInfo, 'amount') * $quantity,
                'package' => $package,
                'billing_interval' => $isSubscription ? 30 : 0,
                'trial_days' => 0,
                'payment_method' => 'paypal',
                'transaction_id' => $transactionId,
                'is_subscription' => $isSubscription,
                'customer_id' => $profileId
            ]);

            if($payment){
                $isSuccess = true;
                $business->post_limit = ($business->post_limit + $postIncrease);
                $business->save();
            }
        }

        if(!$isSuccess){
            return redirect()->to('payment?quantity='.$quantity.'&package='. $package)->withErrors(['error' => 'Something went wrong, try again later.']);
        }

        return redirect()->to('/'. $business->subdomain.'/profile');

    }

}
