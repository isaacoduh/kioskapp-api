<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function index()
    {
        $sellers = Seller::paginate(10);
        return $this->responseSuccess($sellers);
    }

    public function show($id)
    {
        $seller = Seller::where('id', $id)->first();
        return $this->responseSuccess($seller);
    }

    public function ban($id)
    {
        $seller = Seller::where('id',$id)->first();
        $seller->active = false;
        $seller->save();
        return $this->responseSuccess($seller);
    }

    public function activate($id)
    {
        $seller = Seller::where('id',$id)->first();
        $seller->active = true;
        $seller->save();
        return $this->responseSuccess($seller);
    }
}
