<?php

namespace App\Http\Controllers\Api\Stores;

use Error;
use Exception;
use App\Models\subCategory;
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
            'name' => ['required','min:5', 'max:255', 'string'],
            'slug' =>['required','string']
        ]);


        #if there is an error
        if($validator->fails()) {
            return response()->json([
                'errors'=> $validator->errors()->first(),
            ],401);
        };

        #create
        try {
            $saveSubcategory = subCategory::create($validator->validated());

            if ($saveSubcategory){
                return response()->json([
                    'status' => 'success',
                    'message' => 'status Successfully saved',
                    'data' =>$saveSubcategory
                ]);
            } else {
                return response()->json([
                    'error' =>'something went wrong subcategory could not be created '

                ],500);
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
    public function show( $id)
    {
        //
        try{
            $subcategory = subCategory::with('category')->find($id);

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
    public function update(Request $request,  $id)
    {
        //
        $validator = Validator::make($request->all(),[
            'category_id' =>['required'],
            'name' => ['required','min:5', 'max:255', 'string'],
            'slug' =>['required','string'],
        ]);


        #if there is an error
        if($validator->fails()) {
            return response()->json([
                'errors'=> $validator->errors()->first(),
            ],401);
        };

        #create
        try {
               $subcategory = subCategory::find($id);
               if($subcategory != null){
                    if($subcategory->update($validator->validated())){
                        return response()->json([
                            'status' => 'success',
                            'message' => 'subcategory Successfully saved',
                            'data' =>$subcategory
                        ]);
                    }
                } else{

                    return response()->json([
                        'status' => 'success',
                        'message' => 'subcategory not found',
             
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
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        //
        try{
            if (subCategory::destroy($id)){
                return response()->json([
                    'status' => 'success', 
                    'message' => 'SubCategory Deleted successfully'
                ]);
            }  else {
                return response()->json([
                    'errors' => 'SubCategory could not be deleted'
                ], 401);
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
    
}
