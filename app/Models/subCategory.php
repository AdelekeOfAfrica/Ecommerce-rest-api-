<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class subCategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function Category(){
        return $this->belongsTo(Category::class);
    }
}
