<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class KaryawanUpdate extends Model
{
    //


    public static function updateData($request){
    //  return $request->all();
      $data=DB::table('karyawan')
      ->where('id',$request->id)
      ->update(array(
        'nama'=>$request->input('nama')
      ));
      return $data;
    }
}
