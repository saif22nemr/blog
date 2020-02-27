<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class CommentController extends ApiController
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //store comment
        $request->validate([
            'comment' => 'required|min:1|max:300',
            'post_id' => 'required|integer|min:0',
            'user_id' => 'required|integer|min:0',
        ]);
        $data = $request->only(['comment','user_id','post_id']);
        $comment = Comment::create($data);
        return $this->sendOne($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
        $request->validate([
            'comment' => 'required|min:1|max:300',
        ]);
        $comment->comment = $request->comment;
        $comment->save();
        return $this->sendOne($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return $this->sendOne($comment);
    }
}
