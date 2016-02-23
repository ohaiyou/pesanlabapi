<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Other\model_deskripsi;

class OtherController extends Controller
{
    //
    public  function deskripsi($id){
      //name: karim
      //date create: 18 feb 16
      //routes: Route::get('deskripsi/{id}','OtherController@deskripsi');
      //model: Model/Ohther/model_deskripsi.blade.php
      $data=model_deskripsi::getData($id);
      return $data;
    }
}
