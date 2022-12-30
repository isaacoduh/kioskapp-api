<?php

namespace App\Http\Controllers\API\v1\Seller;

use App\Helpers\Upload;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('auth:seller-api');
    }


    public function index($store_id)
    {

        $store = Store::where('id',$store_id)->first();
        $products = Product::where('store_id', $store->id)->get();
        return $this->responseSuccess($products,'Retrieved all products');
    }


    public function store(Request $request)
    {
        $data['title'] = $request['title'];
        $data['description'] = $request['description'];
        $data['price'] = floatval($request['price']);
        $data['image'] = $request['image'];
        $data['store_id'] = $request['store_id'];
        $slugTitle = Str::slug(substr($data['title'], 0,20));
        if(!empty($data['image'])){
            $data['image'] = Upload::upload('image',$data['image'], $slugTitle . '-' . time(), 'images/products');
        }


        $product = Product::create($data);

        return $this->responseSuccess($product->with('store'), 'Created a new Product');
    }

    public function update(){}
    public function delete(){}
}
