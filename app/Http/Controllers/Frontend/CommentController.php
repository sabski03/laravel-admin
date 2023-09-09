<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request){
        if(Auth::check()) {
            $validator = Validator::make($request->all(), [
                'comment_body' => 'required|string'
            ]);

            if($validator->fails()){
                return redirect()->back()->with('message', 'Comment are is mandatory');
            }
            $post = Post::where('slug', $request->post_slug)->where('status', '0')->first();
            if($post){
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => Auth::user()->id,
                    'comment_body' => $request->comment_body,
                ]);
                return redirect()->back()->with('message', 'Comment Added Successfully');

            }else{
                return redirect()->back()->with('message', 'Post Not Found');
            }
        }else{
            return redirect('login')->with('message', 'You Have To Login Before Leaving A Comment');
        }
    }

    public function destroy(Request $request){

        if(Auth::check()) {

            if (Auth::user()->id == 1) {
                // User with id 1 (admin) can delete any comments
                $comment = Comment::find($request->comment_id);
            } else {
                // For non-admin users check if the comment belongs to them
                $comment = Comment::where('id', $request->comment_id)
                    ->where('user_id', Auth::user()->id)
                    ->first();
            }

            if($comment){
                $comment->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Comment Deleted Successfully'
                ]);
            }else{
                return response()->json([
                    'status' => 500,
                    'message' => 'Something Went Wrong'
                ]);
            }



        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Login to Delete this comment'
            ]);
        }
    }
}
