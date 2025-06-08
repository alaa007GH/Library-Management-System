<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class purchase extends Model
{
     protected $fillable = ['book_id','user_id','date','price'];
}
