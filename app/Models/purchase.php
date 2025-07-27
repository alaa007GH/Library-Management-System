<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class purchase extends Model
{
     protected $fillable = ['book_id','user_id','quantity','unit_price','purchase_date','total_price'];


  public function user() {
        return $this->belongsTo(User::class);
    }

    public function book() {
        return $this->belongsTo(Book::class);
    }
}