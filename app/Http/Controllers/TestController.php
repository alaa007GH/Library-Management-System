<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
class TestController extends Controller
{
    
    public function get(){
       $data = Test::get();
       return ['data'=>$data[0]['name']];
    }


    public function create(Request $request){

    $data = Test::create([
        'name' => $request -> name,
        'email' => $request -> email,
        'age' => $request -> age,
    ]);
    return $data;
    }


    public function update(Request $request,$id){
        $data = Test::where('id',$id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'age' => $request->age,
            
        ]);
        return $data;
    }

    
    public function delete(Request $request,$id){
        $data = Test::where('id',$id)->delete();
        return $data;
    }

}

