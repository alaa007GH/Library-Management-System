<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_Book extends Model
{
   protected $fillable = ['book_id','user_id','status'];
 
}
