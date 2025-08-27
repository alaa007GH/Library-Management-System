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
    $bookData = [
        'book_title' => $request->book_title,
        'author' => $request->author,
        'description' => $request->description,
        'price' => $request->price,
        'category_id' => $request->category_id,
        'discount' => $request->discount,
    ];

    if ($request->hasFile('image')) {
        $bookData['image'] = $request->file('image')->store('books', 'public');
    }

    if ($request->hasFile('file')) {
        $bookData['pdf'] = $request->file('file')->store('books/pdfs', 'public');
    }

    $data = Book::create($bookData);
    return $data;
}
public function update(Request $request,$id){
    $bookData = [
        'book_title' => $request->book_title,
        'author' => $request->author,
        'description' => $request->description,
        'price' => $request->price,
        'category_id' => $request->category_id,
        'discount' => $request->discount,
    ];

    if ($request->hasFile('image')) {
        $bookData['image'] = $request->file('image')->store('books', 'public');
    }

    if ($request->hasFile('file')) {
        $bookData['pdf'] = $request->file('file')->store('books/pdfs', 'public');
    }

    $data = Book::where('id',$id)->update($bookData);
    return $data;
}

public function delete(Request $request,$id){
    $data = Book::where('id',$id)->delete();
    return $data;
}

public function uploadImage(Request $request, $id)
{
    $book = Book::findOrFail($id);

    if (!$request->hasFile('image')) {
        return response()->json(['error' => 'No image file provided'], 400);
    }

    $path = $request->file('image')->store('books', 'public');

    $book->image = $path;
    $book->save();

    return response()->json(['message' => 'Image uploaded successfully', 'path' => $path]);
}

public function uploadPdf(Request $request, $id)
{
    $book = Book::findOrFail($id);

    if (!$request->hasFile('file')) {
        return response()->json(['error' => 'No PDF file provided'], 400);
    }

    $path = $request->file('file')->store('books/pdfs', 'public');

    $book->pdf = $path;
    $book->save();

    return response()->json(['message' => 'PDF uploaded successfully', 'path' => $path]);
}
}



