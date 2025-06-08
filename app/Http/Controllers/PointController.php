<?php

namespace App\Http\Controllers;
use App\Models\Point;
use Illuminate\Http\Request;

class PointController extends Controller
{
   public function get(){
        $data = Point::get();
        
        return ['data'=>$data ];
}
public function create(Request $request){
    $data = Point::create([
       
        'user_id' => $request->user_id,
        'points' => $request->points,
        'date' => $request->date,
       
    ]);
    return $data;
}
public function update(Request $request,$id){
    $data = Point::where('id',$id)->update([
        'user_id' => $request->user_id,
        'points' => $request->points,
        'date' => $request->date,
       
    ]);
    return $data;
}

public function delete(Request $request,$id){
    $data = Point::where('id',$id)->delete();
    return $data;
}
}


