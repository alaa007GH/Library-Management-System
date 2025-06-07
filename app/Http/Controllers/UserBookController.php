<?php

namespace App\Http\Controllers;
use App\Models\UserBook;
use Illuminate\Http\Request;

class UserBookController extends Controller
{
   public function get(){
        $data = UserBook::get();
        
        return ['data'=>$data ];
}

public function create(Request $request){
    $data = UserBook::create([
       
        'book_id' => $request->book_id,
        'user_id' => $request->user_id,
        'status' => $request->status,
        
       
    ]);
    return $data;
}

public function update(Request $request,$id){
    $data = UserBook::where('id',$id)->update([

       'book_id' => $request->book_id,
       'user_id' => $request->user_id,
       'status' => $request->status,
       
    ]);
    return $data;
}

public function delete(Request $request,$id){
    $data = UserBook::where('id',$id)->delete();
    return $data;
}
}


