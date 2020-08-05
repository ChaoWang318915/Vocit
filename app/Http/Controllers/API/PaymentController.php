<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Payment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Facades\PayPal;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Plan;
use Stripe\Stripe;
use Stripe\Subscription;
use Stripe\Token;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PaymentController extends BaseController
{
    protected $customerId;
    public function __construct()
    {
    }

    function makePayment(Request $request) {
        Log::info('req', [$request->all()]);

        $package = $request->get('package');
        $quantity = $request->get('quantity');
        $uniqueId = time();

        $plans = Lang::get('plans');
        $packageInfo = Arr::get($plans, $package);
        if(!Arr::get($packageInfo, 'amount')){
            throw new BadRequestHttpException('Payment plan not found');
        }

        $cardNumber = $request->get('card');
        $cardName = $request->get('name');
        $cardCvc = $request->get('cvc');
        $cardMonth = $request->get('month');
        $cardYear = $request->get('year');

        $cardDetails = [
            'number' => $cardNumber,
            'exp_month' => $cardMonth,
            'exp_year' => $cardYear,
            'cvc' => $cardCvc,
        ];

        Log::info($cardDetails);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $tokenData = Token::create([
            'card' => $cardDetails
        ]);

        $token = collect($tokenData)->get('id');

        if($package == 'single' || $package == 'Single'){
            $transactionId = $this->createCharge($token, $packageInfo, $uniqueId);
            $isSubscription = 0;
            $postIncrease = $quantity;
        }
        else{
            $transactionId = $this->createSubscription($token, $packageInfo, $package, $quantity);
            $isSubscription = 1;
            $postIncrease = 3;
        }

        $payment = '';
        if($transactionId){
            $payment = Payment::create([
                'business_id' => (auth()->user()->active_business)->id,
                'amount' => ((Arr::get($packageInfo, 'amount')) * $quantity),
                'package' => $package,
                'billing_interval' => $isSubscription ? 30 : 0,
                'trial_days' => 0,
                'payment_method' => 'card',
                'transaction_id' => $transactionId,
                'is_subscription' => $isSubscription,
                'customer_id' => $this->customerId ? $this->customerId : ''
            ]);

            if($payment){
                $business = auth()->user()->active_business;
                $business->post_limit = ($business->post_limit + $postIncrease);
                $business->save();
            }
        }

        return $this->getResponse($payment, 'Payment successfull');

    }

    protected function createSubscription($token, $packageInfo, $packageName, $quantity = 1) {

        $business = auth()->user()->active_business;
        $plan = Plan::create([
            'amount' => ((Arr::get($packageInfo, 'amount') * 100) * $quantity),
            'currency' => 'usd',
            'interval' => 'month',
            'product' => ['name' => "Subscription for package ". $packageName],
        ]);

        $planId = collect($plan)->get('id');


        $customer = Customer::create([
            'description' => $business->name,
            'email' => $business->email,
            'source' => $token
        ]);

        $customerId = collect($customer)->get('id');
        $this->customerId = $customerId;

        $subscription = Subscription::create([
            'customer' => $customerId,
            'items' => [['plan' => $planId]],
        ]);

        return collect($subscription)->get('id');
    }


    protected function createCharge($token, $packageInfo, $uniqueId) {
        $chargeData = Charge::create([
            'amount' => (Arr::get($packageInfo, 'amount') * 100),
            'currency' => 'usd',
            'source' => $token,
            'description' => 'Payment for post #'. $uniqueId,
        ]);

        return collect($chargeData)->get('id');
    }

    function cancelSubscription() {
        $businessId = (auth()->user()->active_business) ? (auth()->user()->active_business)->id : '';
        $subscription = Payment::where('is_subscription', 1)->where('business_id', $businessId)->first();
        if(!$subscription) {
            throw new BadRequestHttpException('Unable to find any subscription');
        }

        $isCancelled = false;
        $customerId = $subscription->customer_id;
        if($subscription->payment_method == 'card'){
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $recurringSubscription = Subscription::retrieve(
                $subscription->transaction_id
            );

            $response = $recurringSubscription->delete();
            $isCancelled = true;
            Log::info($response);
        }
        else{
            $provider = PayPal::setProvider('express_checkout');
            $response = $provider->cancelRecurringPaymentsProfile($customerId);
            $isCancelled = true;
            Log::info($response);
        }

        if(!$isCancelled){
            throw new BadRequestHttpException('Unable to cancel subscription');
        }

        $subscription->delete();

        return $this->getResponse(true, 'Subscription cancelled');
    }

}
