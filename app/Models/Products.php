<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Products;
use App\Models\subCategory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use HasFactory,Sluggable;

    protected $guarded= [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function getRouteKeyName(){
        return 'slug';
    }

    

    public function sub_categories() {
        return $this->belongsTo(subCategory::class,'subcategory_id');
    }

    public function carts(){
        return $this->hasMany(Cart::class);
    }
}
