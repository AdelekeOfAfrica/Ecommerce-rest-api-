<?php

namespace App\Http\Controllers\Api\Stores;

use Error;
use Exception;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductCategories extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try{
            $categories = Category::all();

            if ($categories->count() > 0){
                return response()->json([
                    'data'=>$categories
                ]);
            } else {
                return response()->json([
                    'message' =>'no stored categories at the moment'
                ]);
            }

        } catch (\Exception $e){
            return response()->json([
                'errors' => 'an exceptional error occured'
            ]);
        } catch (\Error $e){
            return response()->json([
                'errors' => 'an error occured'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    
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

        #create
        try {
           $category = new category();
           $category->name = $request->name;
           $category->slug = Str::slug($request->slug);
           $category->save();
           return response()->json([
            'status'=>"success",
            'message'=>"category successfully added",
            "category"=>$category->name
           ]);

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
        //
        try{
            $category = Category::where('slug',$slug)->firstOrFail();

            if($category != 'null'){
                return response()->json([
                    'data' => $category
                ],200);
            } else{
                return response()->json([
                    'message' => 'category does not exits'
                ],404);
            }

        } catch(\Exception $e){
            return response()->json([
                'errors' => 'an exceptional error has occured'
            ],500);
        } catch(\Error $e){
            return response()->json([
                'errors' => 'an exceptional error has occured'
            ],500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        //
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
            #try to update user 
        try{
            #get the category 
            $update_category = Category::where('slug',$slug)->firstOrFail();
            #check if any error was found
            $update_category->name = $request->name;
            $update_category->slug = Str::slug($request->name);
            $update_category->update();
            return response()->json([
                "status"=>"success",
                "message"=>"category updated",
                "update_category" =>$update_category->name
            ]);
        }catch(\Exception $e){
            return response()->json([
                'errors' => 'an exceptional error has occured'
            ],500);
        } catch(\Error $e){
            return response()->json([
                'errors' => 'an exceptional error has occured'
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        //
        try{
           $category= Category::where('slug',$slug)->delete();
            return response()->json([
                "status"=>"success",
                "message"=>"category successfully deleted",
            ]);

        } catch(\Exception $e){
            return response()->json([
                'errors' => 'an exceptional error has occured'
            ],500);
        } catch(\Error $e){
            return response()->json([
                'errors' => 'an exceptional error has occured'
            ],500);
        }
    }
}
