<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class PaymentController extends Controller
{
    public function __construct() {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function ckeckout(Request $request) 
    {       
        $amount = $this->calculateOrderAmount($request['items'], $request["shipping"]['amount']);

        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
            'receipt_email'=> $request['client']['email'],
            'shipping' => [
                'address' => [
                    'country'=>$request['client']['country'],
                    'state'=>$request['client']['state'],
                    'city'=>$request['client']['city'], 
                    //'line1'=>$request['client']['email'],
                    'postal_code'=>$request['client']['zip_code'],
                ],
                'name'=>$request['client']['name'],
                'phone'=>$request['client']['phone']
            ]
        ]);

        $output = [
            'clientSecret' => $paymentIntent->client_secret,
        ];

        

        return ['ok'=> true, 'clientSecret'=> $output];

    }

    /**
     * this function also exists in the frontend,
     * but it is safer to have it also in the backend
     */
    function calculateOrderAmount(array $items, int $shipping): int {
        $ammout_per_product=[];

        for ($i=0; $i < count($items); $i++) { 
            $result = $items[$i]['product']['price'] * $items[$i]['units'];
            array_push($ammout_per_product, $result);
        }

        $total = array_sum($ammout_per_product);

        return $total + $shipping;
    }

}

