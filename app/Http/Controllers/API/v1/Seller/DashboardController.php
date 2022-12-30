<?php

namespace App\Http\Controllers\API\v1\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Traits\ResponseTrait;
use Illuminate\Database\Schema\Builder;
use Illuminate\Http\Request;
use function Symfony\Component\String\length;

class DashboardController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:seller-api');
    }

    public function index()
    {
        $data = array();
        $storeIds = Store::where('seller_id', auth()->user()->id)->pluck('id')->toArray();

        $aggregateEarnings = Store::where('seller_id', auth()->user()->id)->pluck('earnings')->sum();
        $orderCount = Order::whereIn('store_id', $storeIds)->count();
        $productCount = Product::whereIn('store_id',$storeIds)->count();
        $latestProducts = Product::select(['id','title','price'])->whereIn('store_id',$storeIds)->get();
        $latestOrders = Order::select(['id','total','status','payment_type', 'customer_name'])
            ->whereIn('store_id',$storeIds)
            ->get();
        $data['orders_count'] = $orderCount;
        $data['total_earnings'] = $aggregateEarnings;
        $data['total_products'] = $productCount;
        $data['store_count'] = count($storeIds);
        $data['latest_products'] = $latestProducts;
        $data['latest_orders'] = $latestOrders;


        return $this->responseSuccess($data);
    }
}
