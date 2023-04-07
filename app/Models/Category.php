<?php

namespace App\Models;

use App\Models\subCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function sub_categories(){
        return $this->hasMany(subCategory::class);
    }
}
