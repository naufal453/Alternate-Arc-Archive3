<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller{
    public function store(CommentRequest $request){
        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = Auth::id();
        $comment->post_id = $request->post_id;
        $comment->save();
        return redirect()->back();
    }

    public function destroy(Request $request){
        $comment = Comment::find($request->id);
        if($comment->user_id == Auth::id()){
            $comment->delete();
        }
        return redirect()->back();
    }
}