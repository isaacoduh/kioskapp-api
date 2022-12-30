<?php

namespace App\Http\Controllers\API\v1\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:seller-api');
    }

    public function index()
    {
        $seller = auth()->user();
        $storeIds = Store::where('seller_id',$seller->id)->pluck('id')->toArray();
        $orders = Order::select()->whereIn('store_id',$storeIds)->paginate(5);
        return $this->responseSuccess($orders,'Orders retrieved');

    }

    public function getOrder($id)
    {
        $order = Order::where('id',$id)->with([
            'details' => [
                'product'
            ]
        ])->first();
        return $this->responseSuccess($order, 'Order retrieved!');
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::where('id', $id)->first();
        $status = $request->input('status');

        $order->status = $status;
        $order->save();

        return $this->responseSuccess($order, 'Order updated!');
    }

    public function setOrderToComplete(Request $request, $id)
    {
        $order = Order::where('id',$id)->first();
        if($order->status === 'complete'){
            return $this->responseError(null,'Order is already completed');
        }

        try {
            $order->status = 'complete';
            $earnings = floatval($order->store->earnings) + floatval($order->total) ;
$order->save();
            $order->store->earnings = $earnings;
            $order->store->save();
            return $this->responseSuccess($order);
        } catch (\Exception $exception){
            return $this->responseError($exception->getMessage());
        }
    }
}
