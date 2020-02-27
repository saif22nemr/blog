<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $fillable  = [
    	'comment', 'user_id', 'post_id'
    ];

    //Relationships
    public function posts(){
    	return $this->belongsTo('App\Post','post_id');
    }

    public function user(){
    	return $this->belongsTo('App\User','user_id');
    }
}
