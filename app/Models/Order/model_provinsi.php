<?php

namespace App\Models\Order;
use DB;
use Illuminate\Database\Eloquent\Model;

class model_provinsi extends Model
{
    //
    public static function getData(){
      $province=DB::table('a_province')->get();
      return $province;
    }
}
