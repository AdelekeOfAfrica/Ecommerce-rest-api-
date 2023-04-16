<?php

namespace App\Http\Controllers\Api\Stores;

use Error;
use Exception;
use App\Models\Products;
use App\Models\subCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class productController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try{
            $products = Products::with('sub_categories')->latest()->paginate(20);

            if ($products->count() > 0){
                return response()->json([
                    'status' => 'success',
                    'message' => 'successful',
                    'data'=>$products
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
        //note that this function has sluggable for the slug 
        #inside this function you are to upload but images and image 
        #so if you know you dont have images, ignore the images aspect 

        $validator = Validator::make($request->all(),[
            'subcategory_id'=>['required'],
            'sale_price'=>'required|string|min:4|max:30',
            'name'=>'required|string|min:4|max:30',
            'short_description'=>'required|string|min:4|max:255',
            'description'=>'required|string|min:4|max:255',
            'regular_price'=>'required',
            'sku'=>'required|string|min:2|max:255',
            'stock_status'=>'required|string|min:4|max:255',
            'quantity' => 'required|string|min:1|max:255',
                 
            
        ]);
         #if there is an error
         if($validator->fails()) {
            return response()->json([
                'errors'=> $validator->errors()->first(),
            ],401);
        };
        
        try{
          
        // Save the main image
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                // Validate the uploaded file
                if ($image->isValid() && in_array($image->getClientMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                    $new_name = rand() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('/products/image'), $new_name);
                    $imagedb = '/products/image/' . $new_name;
                }
            }

            // Save the additional images
            // please note that in your form you will have to add the multiple type in the frontend 
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                if(!is_array($images)) {
                    $images = [$images]; // convert to array if it's not already an array
                }
                $imageName = '';
                foreach($images as $image) {
                    $new_name = rand() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('/products/images'), $new_name);
                    $imageName = $imageName . $new_name . ",";
                }
                $imageName = rtrim($imageName, ','); // remove the trailing comma
                $imagesdb = '/products/images/' . $imageName;
            }
                    

            $product = new Products();
            $product->subcategory_id = $request->subcategory_id;
            $product->sale_price = $request->sale_price;
            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->short_description = $request->short_description;
            $product->description = $request->description;
            $product->regular_price = $request->regular_price;
            $product->sku = $request->sku;
            $product->stock_status=$request->stock_status;
            $product->quantity = $request->quantity;
            $product->image = $imagedb;
            $product->images =$imagesdb;
            $product->save();

            }catch (Exception $e){
            return response()->json([
                'errors' => 'an exceptional error occured'
            ]);
            } catch (Error $e){
            return response()->json([
                'errors' => 'an error occured'
            ]);
            }
            $response = [
                'id'=>$product->id,
                'message'=>'product created successfully'
            ];

            return response()->json($response)->setStatusCode(200);

            }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        //
        try{
            $product = Products::where('slug',$slug)->firstOrFail();

            if($product != 'null'){
                return response()->json([
                    'status' =>'success',
                    'message' => 'successful',
                    'data' => $product
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
        #if you are using postman to test the endpoint and it has a file or multiple file attached to it 
        #you are using the post method to send cause form has only get and post 
        #underneth everything key=_method, value=put 
        #to update the images make sure at the frontend @method('put')is being added 
        $validator = Validator::make($request->all(), [
            'subcategory_id' => ['required'],
            'sale_price' => 'required|string|min:4|max:30',
            'name' => 'required|string|min:4|max:30',
            'short_description' => 'required|string|min:4|max:255',
            'description' => 'required|string|min:4|max:255',
            'regular_price' => 'required',
            'sku' => 'required|string|min:2|max:255',
            'stock_status' => 'required|string|min:4|max:255',
            'quantity' => 'required|string|min:1|max:255'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->first(),
            ], 401);
        }
    
        try {
            // Update the product
            $product = Products::where('slug',$slug)->firstOrFail();
            $product->subcategory_id = $request->subcategory_id;
            $product->sale_price = $request->sale_price;
            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->short_description = $request->short_description;
            $product->description = $request->description;
            $product->regular_price = $request->regular_price;
            $product->sku = $request->sku;
            $product->stock_status = $request->stock_status;
            $product->quantity = $request->quantity;
    
             // Save the main image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if ($image->isValid() && in_array($image->getClientMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                $new_name = rand() . '.' . $image->getClientOriginalExtension();
                // Delete the old image if it exists
                if (file_exists(public_path($product->image))) {
                    unlink(public_path($product->image));
                }
                $image->move(public_path('/products/image'), $new_name);
                $product->image = '/products/image/' . $new_name;
            }
        }
    
           // Save the additional images
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            if (!is_array($images)) {
                $images = [$images];
            }
            $image_names = [];
            foreach ($images as $image) {
                if ($image->isValid() && in_array($image->getClientMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                    $new_name = rand() . '.' . $image->getClientOriginalExtension();
                    // Delete the old image if it exists
                    if (file_exists(public_path('/products/images/' . $new_name))) {
                        unlink(public_path('/products/images/' . $new_name));
                    }
                    $image->move(public_path('/products/images'), $new_name);
                    $image_names[] = $new_name;
                }
            }
            $product->images = '/products/images/' . implode(',', $image_names);
        }
    
            $product->save();
        } catch (Exception $e) {
            return response()->json([
                'errors' => 'An exception occurred while updating the product.'
            ], 500);
        }
    
        return response()->json([
            'message' => 'Product updated successfully.',
            'data' => $product
        ]);
    }
    
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        //
        try{
            if (Products::where('slug',$slug)->delete()){
                return response()->json([
                    'status' => 'success', 
                    'message' => 'Product Deleted successfully'
                ]);
            }  else {
                return response()->json([
                    'errors' => 'Product could not be deleted'
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
