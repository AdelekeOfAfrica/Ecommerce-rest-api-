<?php

namespace App\Http\Controllers\Api\contentCreator;

use Error;
use Exception;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BlogCategoryController extends Controller
{
    // 
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
                $blogCategory = BlogCategory::all();

                if ($blogCategory->count() > 0){
                    return response()->json([
                        'data'=>$blogCategory
                    ]);
                } else {
                    return response()->json([
                        'message' =>'no stored BlogCategory at the moment'
                    ]);
                }
            }

        } catch (Exception $e){
            return response()->json([
                'errors' => $e->getMessage()
            ]);
        } catch (Error $e){
            return response()->json([
                'errors' => $e->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $user = Auth::guard('contentCreator-api')->user();

        if(!$user){
            return response()->json([
                'status'=>'error',
                'message'=>'you do not have access '
            ]);
        }

        #create
        try{
            if($user){

                    //defining the rules
                    $validator = Validator::make($request->all(),[
                        'name' => ['required','min:5', 'max:255', 'string'],
                        
                    ]);


                    #if there is an error
                    if($validator->fails()) {
                        return response()->json([
                            'errors'=> $validator->errors()->first(),
                        ],401);
                    }; 
                    $blogCategory = new BlogCategory();
                    $blogCategory->user_id = $user->id;
                    $blogCategory->name = $request->name;
                    $blogCategory->slug = Str::slug($request->slug);
                    $blogCategory->save();
                    return response()->json([
                        'status'=>"success",
                        'message'=>"category successfully added",
                        "category"=>$blogCategory->name
                    ]);
                }

        }catch (Exception $e) {
            return response()->json([
                'errors' => $e->getMessage()
            ], 500);
        } catch (Error $e) {
        return response()->json([
            'errors' => $e->getMessage()
        ], 500);
        }     
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $user = Auth::guard('contentCreator-api')->user();

        if(!$user){
            return response()->json([
                'status'=>'error',
                'message'=>'you do not have access '
            ]);
        }
        //
        try{
            if($user){
                $blogcategory = BlogCategory::where('slug',$slug)->firstOrFail();

            if($blogcategory != 'null'){
                return response()->json([
                    'data' => $blogcategory
                ],200);
            } else{
                return response()->json([
                    'message' => 'Blogcategory does not exits'
                ],404);
            }

            }
        } catch(\Exception $e){
            return response()->json([
                'errors' => $e->getMessage()
            ],500);
        } catch(\Error $e){
            return response()->json([
                'errors' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
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
                 #validate category name 
                $validator = Validator::make($request->all(),[
                    'name' => ["required", "string", "max:255"],
                    
                ]);
                #validate category name   
                if($validator->fails()){
                    return response()->json([
                        'errors' =>$validator->errors()->first(),
                    ],401);
                }
           

                #get the category 
                $update_category = BlogCategory::where('slug',$slug)->firstOrFail();
                #check if any error was found
                $update_category->name = $request->name;
                $update_category->slug = Str::slug($request->name);
                $update_category->update();
                return response()->json([
                    "status"=>"success",
                    "message"=>"category updated",
                    "update_category" =>$update_category->name
                ]);
             }
        }catch(\Exception $e){
            return response()->json([
                'errors' =>$e->getMessage()
            ],500);
        } catch(\Error $e){
            return response()->json([
                'errors' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
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
                $blogcategory= BlogCategory::where('slug',$slug)->delete();
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
