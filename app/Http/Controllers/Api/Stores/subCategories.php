<?php

namespace App\Http\Controllers\Api\Stores;

use Error;
use Exception;
use App\Models\subCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class subCategories extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try{
            $subcategories = SubCategory::with('category')->latest()->paginate(20);

            if ($subcategories->count() > 0){
                return response()->json([
                    'status' => 'success',
                    'message' => 'successful',
                    'data'=>$subcategories
                ]);
            } else {
                return response()->json([
                    'message' =>'no stored subcategories at the moment'
                ]);
            }

        } catch (Exception $e){
            return response()->json([
                'errors' => 'an exceptional error occured'
            ]);
        } catch (Error $e){
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
        $validator = Validator::make($request->all(),[
            'category_id' =>['required'],
            'name' => ['required','min:2', 'max:255', 'string']
        ]);


        #if there is an error
        if($validator->fails()) {
            return response()->json([
                'errors'=> $validator->errors()->first(),
            ],401);
        };

        #create
        try {
           $subcategory = new subCategory();
           $subcategory->category_id = $request->category_id;
           $subcategory->name = $request->name;
           $subcategory->slug = Str::slug($request->name);
           $subcategory->save();

           
           $response = [
            'id'=>$subcategory->id,
            'subcategory'=>$subcategory,
            'message'=>'product created successfully'
        ];

        return response()->json($response)->setStatusCode(200);

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
    public function show( $slug)
    {
        //
        try{
            $subcategory = subCategory::where('slug',$slug)->with('category')->firstOrFail();

            if($subcategory != 'null'){
                return response()->json([
                    'status' =>'success',
                    'message' => 'successful',
                    'data' => $subcategory
                ]);
            } else{
                return response()->json([
                    'message' => 'Subcategory does not exits'
                ]);
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
        $validator = Validator::make($request->all(),[
            'category_id' =>['required'],
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
            $subcategory = subCategory::where('slug',$slug)->firstOrFail();
            $subcategory->category_id = $request->category_id;
            $subcategory->name = $request->name;
            $subcategory->slug = Str::slug($request->name);
            $subcategory->update();
            $response = [
                'id'=>$subcategory->id,
                'subcategory'=>$subcategory,
                'message'=>'product updated successfully'
            ];
    
            return response()->json($response)->setStatusCode(200);  
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
     * Remove the specified resource from storage.
     */
    public function destroy( $slug)
    {
        //
        try{
           $subcategory = subCategory::where('slug',$slug)->delete();
           return response()->json([
            "status"=>"sucess",
            "message"=>"subcategory successfully deleted",
            
           ],200);
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
