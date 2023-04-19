<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        if ($this->collection === null) {
            return ["there is no cart available"];
        }
    
        return $this->collection->map(function ($carts) {
            
            if (isset($carts->products)) {
                $products = collect($carts->products)->map(function ($product) {
                    if (is_object($product)) {
                        return [
                            'product_id' => $product->id,
                            'name' => $product->name,
                            'description' => $product->description,
                            'short_description' => $product->short_description,
                            'regular_price' => $product->regular_price,
                        ];
                    } else {
                        return null;
                    }
                })->filter();// for products 


                $product = $carts->products->first();
                return [
                    'product_id' => $carts->product_id,
                    'product_qty' => $carts->product_qty,
                    'product_price' => $carts->product_price,
                    'product_subtotal' => $carts->sub_total,
                    'products' => [ 
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'short_description' => $product->short_description,
                    'price ' => $product->regular_price,
                ],
                    
                ];
            }
                
        })->toArray();
        
    
    
  
}
    
    
}
