<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class postResources extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         
        return $this->collection->map(function($item){

            return [
                'id'=>$item->id,
                'title'=>$item->title,
                'description'=>$item->description,
                'image'=>$item->image,
                'images'=>$item->images,
                'created_at'=>$item->created_at->format('M D Y'),
                'comments'=>$item->comments->map(function ($comment){
                    return [
                        'id'=>$comment->id,
                        'text'=>$comment->text,
                        'user'=>[
                            'id'=>$comment->user->id,
                            'name'=>$comment->user->name,
                        ],
                    ];
                }),

                'user'=>[
                    'id'=>$item->user->id,
                    'name'=>$item->user->name,

                ],

            ];

        })->toArray();
    }
}
