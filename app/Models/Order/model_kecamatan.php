<?php

namespace App\Models\Order;
use DB;
use Illuminate\Database\Eloquent\Model;

class model_kecamatan extends Model
{
    //
    public static function getData($id){
      $province=DB::table('a_subdistrict')
      ->where('a_regency_code','=',$id)
      ->get();
      return $province;
    }
}
