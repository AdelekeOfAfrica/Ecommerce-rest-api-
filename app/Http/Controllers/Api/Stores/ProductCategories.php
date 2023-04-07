<?php

namespace App\Http\Controllers\Api\Stores;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Exception;
use Error;

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
            'slug' => ['required', 'string']
        ]);


        #if there is an error
        if($validator->fails()) {
            return response()->json([
                'errors'=> $validator->errors()->first(),
            ],401);
        };

        #create
        try {
            $create_category = Category::create($validator->validated());

            if ($create_category){
                return response()->json([
                    'data' =>$create_category
                ]);
            } else {
                return response()->json([
                    'error' =>'something went wrong category could not be created '

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
    public function show(string $id)
    {
        //
        try{
            $category = Category::find($id);

            if($category != 'null'){
                return response()->json([
                    'data' => $category
                ]);
            } else{
                return response()->json([
                    'message' => 'category does not exits'
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
    public function update(Request $request, string $id)
    {
        //
         #validate category name 
         $validator = Validator::make($request->all(),[
            'name' => ["required", "string", "max:255"],
            'slug' => ["required", "string"]
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
            $update_category = Category::find($id);
            #check if any error was found
            if ($update_category !='null') {
                #update the record
                if($update_category->update([
                    'name' => $request->name,
                    'slug' => $request->slug
                ])) {
                    #return a success 
                    return response()->json([
                        'message' => 'record updated ',
                        'data' => $update_category
                    ]);
                }else{
                       return response()->json([
                        'errors' =>'an error occured while updating record '
                       ], 500);
                }


            }else {
                return response()->json([
                   'message' => 'category could not be updated'
                ]);
            }
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
    public function destroy(string $id)
    {
        //
        try{
            if (Category::destroy($id)){
                return response()->json([
                    'message' => 'Category Deleted successfully'
                ]);
            }  else {
                return response()->json([
                    'errors' => 'Category could not be deleted'
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
