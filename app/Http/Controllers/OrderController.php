<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;


class OrderController extends Controller
{

    /**Create one order for one user */
    public function create(Request $request)
    {
        
        $validator = $request->validate([
        "items"    => "required|array|min:1",
        "items.*.product"  => "required|array|min:1",
        "items.*.units"  => "required|int",
        "shipping" => "required|array|min:1",
        "shipping.type" => "required|string",
        "shipping.amount"=> "required|int",
        "client"=> "required|array|min:1",
        "client.id"=> "required|int",
        "client.name"=> "required|string",
        "client.email"=> "required|string",
            "client.phone"=> "required|string",
            "client.country"=> "required|string",
            "client.state"=> "required|string",
            "client.city"=> "required|string",
            "client.zip_code"=> "required|string",
        ]);
        
        //Check if have stock
        $stock_check = $this->checkStock($validator['items']);
        if($stock_check['ok'] === false){
            return ["ok"=> false, "message"=> "Sorry we don't have enough stock", "product"=> $stock_check['product_name']];
        }

        //Execute the payment
        $pc = new PaymentController();
        $payment_result = $pc->ckeckout($request);
        
        if( ! $payment_result['ok'] ) {
            return [ "ok"=> false, "message"=> "Sorry we have a problem with your payment" ];
        }

        //Create the order and the order_items

        /*The user id doesn't comes in the $request, it comes in the token and when
        middleware ISUSER is executed, this middleware add the id to the request*/
        $order = Order::create(['user_id'=> $validator['client']['id']]);


        foreach($validator['items'] as $item){
            $product_id = $item['product']['id'];
            $product_quantity = $item['units'];
            $order_id = $order->id;
            $this->create_order_item( $product_id, $product_quantity, $order_id);
        }

        return response()->json([
            'ok' => true,
            'payment_data'=> $payment_result['clientSecret'],
        ]);
        
    }

    //create a new order_item
    private function create_order_item($product_id, $quantity, $order_id)
    {
        OrderItem::create([
            'product_id' => $product_id,
            'quantity' => $quantity,
            'order_id' => $order_id
        ]);

        //rest quantity of a product
        $this->rest_product_quantity($product_id, $quantity);
    }

    //rest product units
    private function rest_product_quantity($product_id, $quantity){
        //find the product
        $product = Product::findOrFail($product_id);

        //make the rest
        $newQuantity = $product->quantity - $quantity;

        //set the data in database
        $product->quantity = $newQuantity;
        $product->save();
    }

    // check if we have stock
    //   $data = "items": [
    //         { 
    //             "product": { "id": 1, "name": "smart phone", "price": 200 },
    //             "units":3
    //         },
    //         { "product": { "id": 2, "name": "table", "price": 300 },
    //         "units": 1
    //         }
	//     ]  
    private function checkStock($data) 
    {

        foreach($data as $item){
            $product = $item['product'];
            $quantity = $item['units'];
            $product_stock = Product::find($product['id']);
            if( ( $product_stock->quantity - $quantity ) < 0 ){
                return ["ok"=> false, "product_name" => $product_stock->name];
            }
        }
        return ["ok"=> true];
    }


    /** 
     * The user id in this function comes from tue middleware IsUser that extracts it from the token
    */
    public function orders_for_one_user(Request $request)
    {

        //Get user id
        $user = User::find( $request->user_id );

        //Search for orders
        $all_orders_for_one_user = Order::where('user_id', $user['id'] )->get();

        if( count($all_orders_for_one_user) == 0){
            return response()->json(['ok'=> false, 'message'=> "can't find orders for you"]);
        }

        //get all order items
        foreach( $all_orders_for_one_user as $order) {
           $order->order_item;
        }

        //get the products for each order item
        foreach( $all_orders_for_one_user as $order ) {
            foreach($order->order_item as $oi){
                $oi->product;
            }
        }

        return response()->json([
            'ok'=> true,
            'orders' => $all_orders_for_one_user
        ]);
    }

    /**
     * if sended == 0 return pending orders, 
     * if is 1 return sended orders
    */
    private function get_orders($sended){
        $orders = Order::where('sended', $sended)->orderBy('id', 'desc')->get();

        if( count($orders) == 0 ){
            return response()->json([
                'ok'=> false,
                'message' => "No orderd could be found"
            ]);
        }

        //Get user for each order
        foreach($orders as $order){
            $order->user;
        }

        //Get order item for each order
        foreach($orders as $order){
            $order->order_item;
        }

        //Get products for each order item
        foreach($orders as $order){
            foreach($order->order_item as $oi){
                $oi->product;
            }
        }

        
        return response()->json([
            'ok'=> true,
            'orders' => $orders
        ]);
    }

    public function get_sended_orders(){
        return $this->get_orders(1);
    }

    public function get_pendings_orders(){
        return $this->get_orders(0);
    }

    public function order_status(Request $request ){
        $validator = $request->validate([ "order_id"  => "required|integer" ]);

        $order = Order::findOrFail( $validator['order_id'] );

        if( $order->sended === 1 ){
            $order->update(['sended' => false]);
            return response()->json(['ok'=> true, 'order' => $order ]);
        }

        $order->update(['sended' => true]);
        return response()->json(['ok'=> true, 'order' => $order ]);
       
    }

}