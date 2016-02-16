<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class KaryawanDelete extends Model
{
    //
    public static function destroy($id){
      $data=DB::table('karyawan')
      ->where('id',$id)
      ->delete();
      return $data;
    }
}
