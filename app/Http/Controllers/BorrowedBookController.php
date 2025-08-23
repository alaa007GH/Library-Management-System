<?php

namespace App\Http\Controllers;
use App\Models\BorrowedBook;
use Illuminate\Http\Request;
use App\Services\NotificationService;
class BorrowedBookController extends Controller
{
    public function get(){
        $data = BorrowedBook::with('book')->get();

        return ['data'=>$data ];
}
public function create(Request $request){
    $data = BorrowedBook::create([

        'book_id' => $request->book_id,
        'user_id' => auth('sanctum')->user()->id,
        'borrow_date' => $request->borrow_date,
        'due_date' => $request->due_date,
        'borrower_name' => $request->borrower_name,
        'book_status' => $request->book_status,

    ]);
    $notificationService = new NotificationService();
    $notificationService->send(auth('sanctum')->user()->id, 'You have borrowed a new book');
    return $data;
}
public function update(Request $request,$id){
    $data = BorrowedBook::where('id',$id)->update([

        'borrow_date' => $request->borrow_date,
        'due_date' => $request->due_date,
        'borrower_name' => $request->borrower_name,
        'book_status' => $request->book_status,

    ]);
    return $data;
}

public function delete(Request $request,$id){
    $data = BorrowedBook::where('id',$id)->delete();
    return $data;
}
}
