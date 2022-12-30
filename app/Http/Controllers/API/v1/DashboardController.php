<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $data = [];
        $orderCount = Order::where('user_id', auth()->user()->id)->count();

        $data['orders_count'] = $orderCount;
        return $this->responseSuccess($data);
    }
}
