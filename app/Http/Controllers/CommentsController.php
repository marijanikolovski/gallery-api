<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Models\Gallery;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function store($id, CreateCommentRequest $request)
    {
        $validated = $request->validated();

        $gallery = Gallery::with(['images', 'user', 'comments', 'comments.user'])->find($id);
        $comment = new Comment;
        $comment->content = $validated['content'];
        $comment->user()->associate(Auth::user());
        $comment->gallery()->associate($gallery);
        $comment->save();

        return response()->json($comment, 201);
    }


    public function destroy($id)
    {
        $comment = Comment::find($id);
        $comment->delete();

        return response()->json($id, 200);
    }
}
