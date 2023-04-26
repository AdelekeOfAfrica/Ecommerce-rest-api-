<?php

namespace App\Models;

use App\Models\User;
use App\Models\Comment;
use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogPost extends Model
{
    use HasFactory,Sluggable;
    protected $guarded = [];


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function getRouteKeyName(){
        return 'slug';
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function blogCategory(){
        return $this->belongsTo(BlogCategory::class);
    }
    
    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
