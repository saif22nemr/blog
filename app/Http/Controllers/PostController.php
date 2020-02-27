<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('auth');
    }
    public function index()
    {
        $posts = Post::with(['user'])->orderBy('created_at','desc')->paginate(15);
        foreach ($posts as $key => $post) {
            $comments = Comment::where('post_id',$post->id)->whereHas('user')->with('user')->orderBy('created_at','desc')->get();
            $posts[$key]['comments'] = $comments;
        }
        //return $posts;
        return view('home',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) //json
    {
        //
        //return $request->all();
        $request->validate([
            'body'  => 'required|min:10|max:1000',
            'image' => 'required|image'
        ]);
        $post = Post::create([
            'body'    => $request->body,
            'user_id' => Auth()->user()->id,
            'image'   => $request->image->store('image'),
        ]);
        return $this->sendOne($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }
    public function userPosts(User $user){
        if(Auth()->user()->group == 2){
            //this method will return all posts of this user
            $posts = Post::where('user_id',$user->id)->whereHas('user')->with(['user'])->orderBy('created_at','desc')->paginate(15);
            foreach ($posts as $key => $post) {
                $comments = Comment::where('post_id',$post->id)->whereHas('user')->with('user')->orderBy('created_at','desc')->get();
                $posts[$key]['comments'] = $comments;
            }
            //return $posts;
            return view('home',compact('posts'));
        }else{
            return redirect()->intended('/post');
        }
        
    }
    public function myPosts(){
        //this method will return all posts of this user
        $posts = Post::where('user_id',Auth()->user()->id)->whereHas('user')->with(['user'])->orderBy('created_at','desc')->paginate(15);
        foreach ($posts as $key => $post) {
            $comments = Comment::where('post_id',$post->id)->whereHas('user')->with('user')->orderBy('created_at','desc')->get();
            $posts[$key]['comments'] = $comments;
        }
        //return $posts;
        return view('home',compact('posts'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
        $request->validate([
            'body' => 'min:10|max:1000',
            'image'=> 'image',
        ]);
        if(isset($request->image)){
            Storage::disk('image')->delete($post->image);
            $post->image = $request->image->store('image');
        }
        if(isset($request->body)){
            $post->body = $request->body;
        }
        $post->save();
        return redirect()->intended('/post');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        Storage::disk('image')->delete($post->image);
        return $this->sendOne($post);
    }
}
