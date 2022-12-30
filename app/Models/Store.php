<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','type','seller_id','earnings'];

    public function seller()
    {
        return $this->belongsTo(Seller::class)->select('id','name','email');
    }

    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('id','desc');
    }

    public function orders()
    {
        return $this->hasMany(Order::class)->orderBy('id','desc');
    }
}
