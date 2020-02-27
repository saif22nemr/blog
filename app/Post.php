<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
    	'body' , 'user_id', 'image'
    ];
    public function user(){
    	return $this->belongsTo('App\User','user_id');
    }
    public function comments(){
    	return $this->hasMany('App\Comment','post_id');
    }
}
