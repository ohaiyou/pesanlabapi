<?php

namespace App\Models\Order;
use DB;
use Illuminate\Database\Eloquent\Model;

class model_kabupaten extends Model
{
    //
    public static function getData($id){
      $province=DB::table('a_regency')
      ->where('a_province_code','=',$id)
      ->get();
      return $province;
    }
}
