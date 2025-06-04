<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function get(){
        $data = Category::get();
        
        return ['data'=>$data ];
}
public function create(Request $request){
    $data = Category::create([
        'name' => $request->name,
        
    ]);
    return $data;
}
public function update(Request $request,$id){
    $data = Category::where('id',$id)->update([
        'name' => $request->name,
       
    ]);
    return $data;
}

public function delete(Request $request,$id){
    $data = Category::where('id',$id)->delete();
    return $data;
}
}
