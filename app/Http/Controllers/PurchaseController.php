<?php

namespace App\Http\Controllers;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function get(){
        $data = Purchase::get();
        
        return ['data'=>$data ];
}

public function create(Request $request){
    $data = Purchase::create([
       
        'book_id' => $request->book_id,
        'user_id' => $request->user_id,
        'date' => $request->date,
        'price' => $request->price,
       
    ]);
    return $data;
}

public function update(Request $request,$id){
    $data = Purchase::where('id',$id)->update([
       
        'book_id' => $request->book_id,
        'user_id' => $request->user_id,
        'date' => $request->date,
        'price' => $request->price,
       
    ]);
    return $data;
}

public function delete(Request $request,$id){
    $data = Purchase::where('id',$id)->delete();
    return $data;
}
}

