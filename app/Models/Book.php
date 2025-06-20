<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['book_title','author','description','price','review','category_id'];

public function borrowedBook(){
return $this->hasMany(BorrowedBook::class,'category_id');}

public function users(){

  return $this->belongsToMany(User::class, 'borrowed_books', 'book_id', 'user_id');

}

}
