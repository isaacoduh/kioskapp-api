<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index()
    {
        $stores = Store::paginate(10);
        return $this->responseSuccess($stores);
    }

    public function show($id)
    {
        $store = Store::where('id',$id)->first();
        return $this->responseSuccess($store);
    }

    public function ban($id)
    {
        $store = Store::where('id',$id)->first();
        $store->active = false;
        $store->save();
        return $this->responseSuccess($store);
    }

    public function activate($id)
    {
        $store = Store::where('id',$id)->first();
        $store->active = true;
        $store->save();
        return $this->responseSuccess($store);
    }
}
