<?php

namespace App\Http\Controllers\API\v1\Seller;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    use ResponseTrait;
    public function __construct()
    {
        $this->middleware('auth:seller-api');
    }

    // Retrieve All Stores created by the logged in user
    public function index()
    {
        $seller = auth()->user();
        $stores = Store::where('seller_id', $seller->id)->get();

        return $this->responseSuccess($stores,'Retrieved all stores');
    }

    public function store(Request $request)
    {
        $data['title'] = $request['title'];
        $data['description'] = $request['description'];
        $data['type'] = $request['type'];
        $data['seller_id'] = auth()->user()->id;

        $store = Store::create($data);

        return $this->responseSuccess($store, "Store created successfully");

    }
}
