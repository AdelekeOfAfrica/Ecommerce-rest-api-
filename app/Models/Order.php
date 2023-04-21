<?php

namespace App\Models;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $guarded=[];

    
   public function user(){
    return $this->belongsTo(User::class);
   }

   public function cart(){
    return $this->hasMany(Cart::class);
   }
}