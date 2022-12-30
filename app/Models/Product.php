<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','description','price','image','store_id'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class)->select('id','title');
    }

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if(is_null($this->image) || $this->image === ""){
            return null;
        }
        return url('')."/images/products/".$this->image;
    }
}
