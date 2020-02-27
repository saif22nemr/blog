<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ApiController extends Controller
{
    public function sendAll(Collection $collection,$code = 200){
    	return response()->json(['data'=>$collection],$code);
    }
    public function sendOne(Model $model,$code = 200){
    	return response()->json(['data'=>$model],$code);
    }
    public function sendData($data,$code = 200){
    	return response()->json($data,$code);
    }
    public function sendError($message, $code){
    	return response()->json(['error' => $message],$code);
    }

}
