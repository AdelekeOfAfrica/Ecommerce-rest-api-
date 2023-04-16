<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Products;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class subCategory extends Model
{
    use HasFactory,Sluggable;
    protected $guarded = [];
    protected $fillable = ["category_id","name","slug"];

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

    public function Category(){
        return $this->belongsTo(Category::class);
    }

    public function products() {
        return $this->hasMany(Products::class);
    }
}
