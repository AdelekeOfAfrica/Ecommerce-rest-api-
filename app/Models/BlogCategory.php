<?php

namespace App\Models;

use App\Models\User;
use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogCategory extends Model
{
    use HasFactory,Sluggable;
    protected $guarded = [];

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

    public function blogPost() 
    {
        return $this->hasMany(BlogPost::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
