<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class KaryawanStore extends Model
{
    //
    public static function store($request){
      DB::table('karyawan')->insert(array(
        'id'=>$request->input('id'),
        'nama'=>$request->input('nama')
      ));
  }
}
