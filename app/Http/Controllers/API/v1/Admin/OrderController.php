<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index()
    {
        $orders = Order::with('store')
            ->paginate(10);
        return $this->responseSuccess($orders);
    }

    public function show($id)
    {
        $order = Order::where('id',$id)
            ->with('details')
            ->with('user')
            ->with('store')
            ->first();

        return $this->responseSuccess($order,'Order retrieved');
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::where('id', $id)->first();
        $status = $request->input('status');

        $order->status = $status;
        $order->save();

        return $this->responseSuccess($order,'Order updated by admin');
    }
}
