<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StripeController extends Controller
{
    public function processPayment(Request $request)
    {
        try {

            $stripe = new \Stripe\StripeClient('sk_test_51OcS0WFMvJtivviay18VgkJ59TGOfX5OfSejbR0D5K2QK2EQxJdGmWvBHd6CTF2TEUJT0kJnOXvlAJKYNYYdalqY00jwN4jIHl');
            $response = $stripe->paymentIntents->create([
                'amount' => 2000,
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
                'confirm' => true
            ]);

            return response()->json(['status' => 'success', 'payment_intent' => $response]);

        } catch (HttpException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
