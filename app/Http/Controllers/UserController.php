<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Controllers\ApiController;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends ApiController
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check if the auth is admin
        if(Auth::user()->group == 2){
            //make sorting
            if(isset($request->filter)){
                $availbleSorting = ['name','username', 'group','created_at','email','id'];
                if(isset($request->order) and ($request->order == 'asc' || $request->order == 'desc')){
                    $order = $request->order;
                }else
                    $order = 'asc';
                if(in_array($request->filter, $availbleSorting)){
                    $filter = $request->filter;
                }else{
                    $filter = 'created_at';
                }
            }else{
                $filter = 'created_at';
                $order = 'desc';
            }
            //end sort
            //searching
            if(isset($request->search) and strlen($request->search) > 0){
                $search = $request->search;
                $availbleSearching = ['name','username', 'email', 'id'];
                if(isset($request->searchType) and in_array($request->searchType, $availbleSearching)){
                    $searchType = $request->searchType;
                }else{
                    $searchType = 'name';
                }
                if($searchType != 'id'){
                    $users = User::where($searchType,'like',"%".$search."%")->orderBy($filter,$order)->paginate(20);
                }else{
                    $users = User::where($searchType,$search)->orderBy($filter,$order)->paginate(20);
                }
            }else{ //if not searching
                $users = User::orderBy($filter,$order)->paginate(20);
                $searchType = 'name';
            }
            //get count of posts of all users
            foreach ($users as $key => $user) {
                $user['postCount'] = $user->posts()->count();
            }
            return view('user',compact(['users','filter','order','searchType']));
        }else{
            return redirect()->intended('/post');
        }
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('profile',compact('user'));
    }
    public function profile(){
        $user = Auth::user();
        $count = [
            'post'    => Post::all()->count(),
            'comment' => Comment::all()->count(),
            'user'    => User::where('group',1)->get()->count(),
            'admin'   => User::where('group',2)->get()->count(),
        ];
        //return $count;
        return view('profile',compact(['user','count']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user();
        $edit = true;
        return view('auth.register',compact(['user','edit']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)//json
    {
        if(Auth::user()->group == 2){ //this method only used by admin
            //return $request->all();
            $request->validate([
                'name'    => 'string|min:2|max:150',
                'username'=> 'string|min:2|max:50|unique:users',
                'email'   => 'email|unique:users',
                'password'=> 'string|min:8|max:300|confirmed',
                'image'   => 'image',
                'group'   => 'in:1,2',
            ]);

            $data = $request->only(['name','username','email','group']);
            $user->fill($data);
            if(isset($request->image)){
                $user->image = $request->image->store('image');
            }
            if(isset($request->password)){
                $user->password = Hash::make($request->password);
            }
            $user->save();
            return $this->sendOne($user);
        }else{
            return $this->sendError('You unauthorized to update user!',401);
        }
    }
    public function updateProfile(Request $request)//json
    {
        $user = Auth::user();
        $request->validate([
            'name'    => 'string|min:2|max:150',
            'username'=> 'string|min:2|max:50',
            'email'   => 'email',
            'password'=> 'max:300|confirmed',
            'image'   => 'image',
        ]);
        //check if unique
        $check = User::where('username',$request->username)->first();
        if(isset($check->id)){

        }
        $check = User::where('email',$request->email)->first();
        if(isset($check->id)){
            
        }
        $data = $request->only(['name','username','email']);
        $user->fill($data);
        if(isset($request->image)){
            Storage::disk('image')->delete($user->image);
            $user->image = $request->image->store('image');
        }
        if(isset($request->password)){
            if(strlen($request->password) != 0 and strlen($request->password) >= 8)
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return redirect()->intended('/profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) // json
    {
        $user->delete();
        if($user->image != null)
            Storage::disk('image')->delete($user->image);
        return $this->sendOne($user);
    }
}
