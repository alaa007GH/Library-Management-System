<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Test;

class ProductController extends Controller
{
    public function get(){
        $data1 = Test::get();
        $data2 = Product::get();

        return ['data'=>$data1,$data2 ];
}

    public function create(Request $request){
        $data = Product::create([
            'name' => $request->name,
            'rate' => $request->rate,
            'description' => $request->description,
            'price' =>$request->price
        ]);
        return $data;
    }

    public function update(Request $request,$id){
        $data = Product::where('id',$id)->update([
            'name' => $request->name,
            'rate' => $request->rate,
            'description' => $request->description,
            'price' =>$request->price
        ]);
        return $data;
    }


    public function delete(Request $request,$id){
        $data = Product::where('id',$id)->delete();
        return $data;
    }

}
