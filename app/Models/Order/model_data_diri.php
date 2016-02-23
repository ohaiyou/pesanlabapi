<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use Cart;
use DB;
class model_data_diri extends Model
{
    //
    public static function postData($request){
      /*echo $request->nama_pasien;
      echo $request->dob;
      echo $request->gender;
      echo $request->alamat;
      echo $request->telp;
      echo $request->layanan;*/

      


      session(['data_diri'=>$request->nama_pasien."#".$request->birth."#".$request->gender."#".$request->address."#".$request->city_code."#".$request->phone."#".$request->service."#".$request->test_date."#".$request->home_service_times]);
      return session('data_diri');
    }

}
