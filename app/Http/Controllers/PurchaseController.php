<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
class PurchaseController extends Controller
{
    public function get(){
        $data = Purchase::get();
        
        return ['data'=>$data ];
}

public function create(Request $request){
    $data = Purchase::create([
        'user_id' => $request->user_id,
        'book_id' => $request->book_id,
        'price' =>$request->price,
        'date' =>$request->date,

    ]);
    return $data;
}

public function update(Request $request,$id){
    $data = Purchase::where('id',$id)->update([
        'user_id' => $request->user_id,
        'book_id' => $request->book_id,
        'price' =>$request->price,
        'date' =>$request->date,
    ]);
    return $data;
}

public function delete(Request $request,$id){
    $data = Purchase::where('id',$id)->delete();
    return $data;
}
}
