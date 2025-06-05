<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowedBook extends Model
{
    protected $fillable = ['borrow_date','due_date','borrower_name','book_status'];
    public function book(){

    return $this->belongsTo(Book::class,'category_id');
    }
}
