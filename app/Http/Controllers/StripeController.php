<?php

namespace App\Http\Controllers;

use App\Jobs\SendPaymentSuccessEmail;
use Illuminate\Http\Request;
use App\Models\Payment;

class StripeController extends Controller
{
    public function index()
    {
        $products = [
            ['name' => 'Mobile Phone', 'price' => 20],
            ['name' => 'Laptop', 'price' => 800],
            ['name' => 'Headphones', 'price' => 50],
            ['name' => 'Smart Watch', 'price' => 150],
            ['name' => 'Tablet', 'price' => 300],
            ['name' => 'Camera', 'price' => 500],
            ['name' => 'Keyboard', 'price' => 25],
            ['name' => 'Mouse', 'price' => 15],
            ['name' => 'Monitor', 'price' => 200],
            ['name' => 'Printer', 'price' => 100],
        ];

        return view('products.index', compact('products'));
    }

    public function stripe(Request $request)
{
    $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
    $response = $stripe->checkout->sessions->create([
        'line_items' => [
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $request->product_name,
                    ],
                    'unit_amount' => $request->price * 100,
                ],
                'quantity' => $request->quantity,
            ],
        ],
        'mode' => 'payment',
        'success_url' => route('success').'?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => route('cancel'),
    ]);

    if (isset($response->id) && $response->id != '') {
        session()->put('product_name', $request->product_name);
        session()->put('quantity', $request->quantity);
        session()->put('price', $request->price);
        return redirect($response->url);
    } else {
        return redirect()->route('cancel');
    }
}



public function success(Request $request)
{
    if (isset($request->session_id)) {
        $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
        $response = $stripe->checkout->sessions->retrieve($request->session_id);

        $product_name = session()->get('product_name');
        $quantity = session()->get('quantity');
        $price = session()->get('price');

        if (is_null($product_name) || is_null($quantity) || is_null($price)) {
            return redirect()->route('cancel')->with('error', 'Session data missing');
        }

        $payment = new Payment();
        $payment->payment_id = $response->id;
        $payment->product_name = $product_name;
        $payment->quantity = $quantity;
        $payment->amount = $price;
        $payment->currency = $response->currency;
        $payment->payer_name = $response->customer_details->name;
        $payment->payer_email = $response->customer_details->email;
        $payment->payment_status = $response->status;
        $payment->payment_method = "Stripe";
        $payment->save();

        session()->forget('product_name');
        session()->forget('quantity');
        session()->forget('price');

        // Dispatch the email job
        SendPaymentSuccessEmail::dispatch($payment);

        return "Payment is successful";
    } else {
        return redirect()->route('cancel');
    }
}




    public function cancel()
    {
        return "Cancel";
    }

}
