<?php

namespace App\Http\Controllers;

use Error;
use Exception;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    //

    public function store(Request $request ){
        $user = Auth::guard('user-api')->user();

        if(!$user){
            return response()->json([
                'status'=>'error',
                'message'=>'you do not have access '
            ]);
        }
        try{
            if($user){
                $validator = Validator::make($request->all(),[
                    'text' => ['required','min:5',  'string'],
                    'blog_post_id' => ['required'],
                    
                ]);


                #if there is an error
                if($validator->fails()) {
                    return response()->json([
                        'errors'=> $validator->errors()->first(),
                    ],401);
                }; 

                $comment = new Comment();
                $comment->user_id= $user->id;
                $comment->blog_post_id = $request->blog_post_id;
                $comment->text = $request->text;
                $comment->save();

                return response()->json([
                    'status'=>"success",
                    'message'=>"comment successfully added",
                    "comment"=>$comment,
                ]);
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
    public function destroy($id){
        $user = Auth::guard('user-api')->user();

        if(!$user){
            return response()->json([
                'status'=>'error',
                'message'=>'you do not have access '
            ]);
        }
        try{
            if($user){
                $comment= Comment::where('id',$id)->delete();
                return response()->json([
                    "status"=>"success",
                    "message"=>"comment successfully deleted",
                    
                ]);
            }  else {
                return response()->json([
                    "status"=>"success",
                    "message"=>"Comment not found", 
                ]);
            }
        } catch(Exception $e){
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
}
