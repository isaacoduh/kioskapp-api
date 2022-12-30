<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ResponseTrait;
    // get all stores
    public function listStores()
    {
        $stores = Store::with('seller')->paginate();
        return $this->responseSuccess($stores);
    }

    // get a single store with id
    public function getStore($id)
    {
        $store = Store::where('id',$id)->with('products')->get();
        return $this->responseSuccess($store);
    }
}
