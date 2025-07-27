<?php

namespace App\Http\Controllers;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function get(){
        $data = Book::get();
        
        return ['data'=>$data ];
}
public function create(Request $request){
    $data = Book::create([
       
        'book_title' => $request->book_title,
        'author' => $request->author,
        'description' => $request->description,
        'price' => $request->price,
        'category_id' => $request->category_id,
        'discount' => $request->discount,
    ]);
    return $data;
}
public function update(Request $request,$id){
    $data = Book::where('id',$id)->update([
        
        'book_title' => $request->book_title,
        'author' => $request->author,
        'description' => $request->description,
        'price' => $request->price,
        'category_id' => $request->category_id,
        'discount' => $request->discount,
    ]);
    return $data;
}

public function delete(Request $request,$id){
    $data = Book::where('id',$id)->delete();
    return $data;
}
}


