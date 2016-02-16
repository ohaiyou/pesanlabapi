<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class TeModel extends Model
{
    //
    public static function getData($request){
    //return $request->input('nama');
    //$data=DB::table('patient')->get();
    //return $data;

    DB::table('patient')
    ->insert(array(
      'patient_name'=>$request->input('nama'),
    ));
  }
}
