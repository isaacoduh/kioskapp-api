<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        $data['user_id'] = auth()->user()->id;
        $data['store_id'] = $request['store_id'];
        $data['items'] = $request['items'];
        $data['total'] = $request['total'];
        $data['hint'] = $request['hint'];
        $data['customer_name'] = auth()->user()->name;
        $data['customer_phone'] = $request['customer_phone'];
        $data['customer_address'] = $request['customer_address'];

        try {
            $order = [
                'user_id' => $request->user()->id,
                'store_id' => $data['store_id'],
                'total' => $data['total'],
                'hint' => $data['hint'] ? $data['hint'] : '',
                'customer_name' => $data['customer_name'],
                'customer_address' => $data['customer_address'],
                'customer_phone' => $data['customer_phone']
            ];

            $order_id = DB::table('orders')->insertGetId($order);
            foreach ($request['items'] as $i)
            {
                $product = Product::find($i['id']);
                $price = $i['price'];
                $order_detail = [
                    'order_id' => $order_id,
                    'product_id' => $i['id'],
                    'price' => $price,
                    'quantity' => $i['quantity']
                ];

                DB::table('order_details')->insert($order_detail);
            }
            // notification
//            // mail
            return $this->responseSuccess($order,'Order placed successfully');
//
        } catch (\Exception $exception)
        {
return $this->responseError($exception, 'Invalid data');
        }
    }
}
