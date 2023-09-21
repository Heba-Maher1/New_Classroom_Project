<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\Payments\StripePayment;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Stripe\Charge;
use Stripe\StripeClient;

class PaymentsController extends Controller
{

    public function create(StripePayment $stripe, Subscription $subscription)
    {
        return $stripe->createCheckoutSession($subscription);
    }

    public function store(Request $request)
    {

        $subscription = Subscription::findOrFail($request->subscription_id);

        $stripe = new StripeClient(config('services.stripe.secret_key'));
        try {

            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $subscription->price * 100,
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return [
                'clientSecret' => $paymentIntent->client_secret,
            ];
        } catch (Error $e) {
            return Response::json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function success(Request $request)
    {
        return view('payments.success');
    }
    public function cancel(Request $request)
    {
        return view('payments.cancel');
    }
}
