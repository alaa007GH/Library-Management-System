<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['name','author_id','price'];

    public function user(){
        return $this->belongsTo(User::class,'author_id');
    }



public function purchase(){
        return $this->belongsToMany(User::class,'purchases');
    }







}