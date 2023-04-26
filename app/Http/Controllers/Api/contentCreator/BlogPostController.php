<?php

namespace App\Http\Controllers\Api\contentCreator;

use Error;
use Exception;
use App\Models\BlogPost;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\postResources;
use Illuminate\Support\Facades\Validator;

class BlogPostController extends Controller
{
    //

    public function index(){

        $user = Auth::guard('contentCreator-api')->user();

        if(!$user){
            return response()->json([
                'status'=>'error',
                'message'=>'you do not have access '
            ]);
        }

        if($user){
            $blog_post = BlogPost::orderBy('created_at','desc')->get();
    
            // If there are no carts with products, return a message
            if ($blog_post->isEmpty()) {
                return new postResources(null);
            }
        
            return new postResources($blog_post);
        }
        
         
    }
    #for this function to work, you must upload images,images
    public function store(Request $request){
        $user = Auth::guard('contentCreator-api')->user();

        if(!$user){
            return response()->json([
                'status'=>'error',
                'message'=>'you do not have access '
            ]);
        }
        try {
            if($user){

                $validator = Validator::make($request->all(),[
                    'blog_category_id'=>['required'],
                    'title'=>'required|string|min:4|max:255',
                    'description'=>'required|string|min:4|',       
                    
                ]);
                 #if there is an error
                 if($validator->fails()) {
                    return response()->json([
                        'errors'=> $validator->errors()->first(),
                    ],401);
                };
                #single image 
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
    
                    // Validate the uploaded file
                    if ($image->isValid() && in_array($image->getClientMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                        $new_name = rand() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('/blog/image'), $new_name);
                        $imagedb = '/blog/image/' . $new_name;
                    }
                }
                #multiple images
                if ($request->hasFile('images')) {
                    $images = $request->file('images');
                    if(!is_array($images)) {
                        $images = [$images]; // convert to array if it's not already an array
                    }
                    $imageName = '';
                    foreach($images as $image) {
                        $new_name = rand() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('/blog/images'), $new_name);
                        $imageName = $imageName . $new_name . ",";
                    }
                    $imageName = rtrim($imageName, ','); // remove the trailing comma
                    $imagesdb = '/blog/images/' . $imageName;
                }
                  
                $post = new BlogPost();
                $post->user_id = $user->id;
                $post->title = $request->title;
                $post->blog_category_id = $request->blog_category_id;
                $post->slug = Str::slug($request->title);
                $post->description = $request->description;
                $post->image = $imagedb;
                $post->images=$imagesdb;
                $post->save();

            }

            return response()->json([
                'status'=>'success',
                'message'=>'Blog post successfully created',
                'post'=>$post,
            ],200);

        }catch(Exception $e){
            return response()->json([
                'errors' => $e->getMessage()
            ],500);
        }
        catch(Error $e){
            return response()->json([
                'errors' => $e->getMessage()
            ],500);
        }
    }

    public function show($slug)
    {
        $user = Auth::guard('contentCreator-api')->user();

        if(!$user){
            return response()->json([
                'status'=>'error',
                'message'=>'you do not have access '
            ]);
        }
        try{
            if($user){
                $post = BlogPost::where('slug',$slug)->firstOrFail();

            if($post != 'null'){
                return response()->json([
                    'status' =>'success',
                    'message' => 'successful',
                    'data' => $post
                ]);
            }else{
                return response()->json([
                    'message' => 'Blog post does not exits'
                ]);
            }  
            }

        }catch(Exception $e){
            return response()->json([
                'errors' => $e->getMessage()
            ],500);
        }
        catch(Error $e){
            return response()->json([
                'errors' => $e->getMessage()
            ],500);
        }
    }

    public function update(Request $request , $slug)
    {
        $user = Auth::guard('contentCreator-api')->user();

        if(!$user){
            return response()->json([
                'status'=>'error',
                'message'=>'you do not have access '
            ]);
        }
        try {
            if($user){

                $validator = Validator::make($request->all(),[
                    'blog_category_id'=>['required'],
                    'title'=>'required|string|min:4|max:255',
                    'description'=>'required|string|min:4|',       
                    
                ]);
                 #if there is an error
                 if($validator->fails()) {
                    return response()->json([
                        'errors'=> $validator->errors()->first(),
                    ],401);
                };
                #single image 
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
    
                    // Validate the uploaded file
                    if ($image->isValid() && in_array($image->getClientMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                        $new_name = rand() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('/blogs/image'), $new_name);
                        $imagedb = '/blogs/image/' . $new_name;
                    }
                }
                #multiple images
                if ($request->hasFile('images')) {
                    $images = $request->file('images');
                    if(!is_array($images)) {
                        $images = [$images]; // convert to array if it's not already an array
                    }
                    $imageName = '';
                    foreach($images as $image) {
                        $new_name = rand() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('/blog/images'), $new_name);
                        $imageName = $imageName . $new_name . ",";
                    }
                    $imageName = rtrim($imageName, ','); // remove the trailing comma
                    $imagesdb = '/blog/images/' . $imageName;
                }
                  
                $post = BlogPost::where('slug',$slug)->firstOrFail();
                $post->user_id = $user->id;
                $post->title = $request->title;
                $post->blog_category_id = $request->blog_category_id;
                $post->slug = Str::slug($request->title);
                $post->description = $request->description;
                $post->image = $imagedb;
                $post->images=$imagesdb;
                $post->update();

            }

            return response()->json([
                'status'=>'success',
                'message'=>'Blog post successfully updated',
                'post'=>$post,
            ],200);

        }catch(Exception $e){
            return response()->json([
                'errors' => $e->getMessage()
            ],500);
        }
        catch(Error $e){
            return response()->json([
                'errors' => $e->getMessage()
            ],500);
        }        
    }

    
    public function destroy($slug){
        //  
        $user = Auth::guard('contentCreator-api')->user();

        if(!$user){
            return response()->json([
                'status'=>'error',
                'message'=>'you do not have access '
            ]);
        }
        
        try{
            if($user){
                $blogpost= BlogPost::where('slug',$slug)->delete();
                return response()->json([
                    "status"=>"success",
                    "message"=>"Blogcategory successfully deleted",
                ]);
    
            } else {
                return response()->json([
                    "status"=>"success",
                    "message"=>"BlogCategory not found", 
                ]);
            }
        } catch(\Exception $e){
            return response()->json([
                'errors' => $e->getMessage()
            ],500);
        }
        catch(\Error $e){
            return response()->json([
                'errors' => $e->getMessage()
            ],500);
        }
    }
}
