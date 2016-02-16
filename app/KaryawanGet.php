<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class KaryawanGet extends Model
{
    //
    public static function getData(){
    $data=DB::table('karyawan')->get();
    return $data;
  }
}
