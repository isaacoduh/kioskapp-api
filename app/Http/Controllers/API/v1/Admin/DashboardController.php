<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Store;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $data = array();

        $storeCount = Store::count();
        $orderCount = Order::count();
        $productCount = Product::count();
        $sellerCount = Seller::count();

        $totalEarnings = Store::pluck('earnings')->sum();

        $latestOrders = Order::select(['id','total','status','payment_type','customer_name'])->get(5);

        $data['stores_count'] = $storeCount;
        $data['orders_count'] = $orderCount;
        $data['products_count'] = $productCount;
        $data['sellers_count'] = $sellerCount;

        $data['total_earnings'] = $totalEarnings;
        $data['latest_orders'] = $latestOrders;




        return $this->responseSuccess($data);
    }
}
