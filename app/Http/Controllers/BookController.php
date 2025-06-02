<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;


class BookController extends Controller
{
    public function get(){
        $data = Book::get();
        
        return ['data'=>$data ];
}
public function create(Request $request){
    $data = Book::create([
        'name' => $request->name,
        'author_id' => $request->author_id,
        'price' =>$request->price
    ]);
    return $data;
}
public function update(Request $request,$id){
    $data = Book::where('id',$id)->update([
        'name' => $request->name,
        'author_id' => $request->author_id,
        'price' =>$request->price
    ]);
    return $data;
}

public function delete(Request $request,$id){
    $data = Book::where('id',$id)->delete();
    return $data;
}
}

