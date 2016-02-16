<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class KaryawanEdit extends Model
{

    public static function getData($id){
      $data=DB::table('karyawan')
      ->where('id',$id)
      ->get();
      return $data;
    }
}
