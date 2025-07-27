<?php

namespace App\Http\Controllers;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
     public function get(){
        $data = Review::get();
        
        return ['data'=>$data ];
}

public function create(Request $request){
    $data = Review::create([
       
        'user_id' => $request->user_id,
        'action' => $request->action,
        
    ]);
    return $data;
}

public function update(Request $request,$id){
    $data = Review::where('id',$id)->update([
        'user_id' => $request->user_id,
        'action' => $request->action,
       
    ]);
    return $data;
}

public function delete(Request $request,$id){
    $data = Review::where('id',$id)->delete();
    return $data;
}
}
