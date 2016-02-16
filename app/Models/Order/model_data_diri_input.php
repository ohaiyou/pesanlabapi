<?php

namespace App\Models\Order;
use Cart;
use Illuminate\Database\Eloquent\Model;

class model_data_diri_input extends Model
{
    //
    public static function postData($request){
      /*echo $request->nama_pasien;
      echo $request->dob;
      echo $request->gender;
      echo $request->alamat;
      echo $request->telp;
      echo $request->layanan;*/

      session(['data_diri'=>$request->nama_pasien."#".$request->dob."#".$request->gender."#".$request->alamat."#".$request->city_code."#".$request->telp."#".$request->layanan]);
      return session('data_diri');


    }
}
