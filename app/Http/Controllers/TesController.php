<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\TeModel;

class TesController extends Controller
{
  public function tes(Request $request){

    $data=TeModel::getData($request);
    return $data;
  }

  public function input(){

    return View('tes');
  }

}
