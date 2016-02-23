<?php

namespace App\Models\Order;
use Cart;
use Illuminate\Database\Eloquent\Model;

class model_data_diri_input extends Model
{
    //
    public static function postData($request){
      //name: karim
      //date create:23 Feb 16
      //routes: Route::get('order/datadiriview','OrderController@data_diri_view');
      //model: Model/Order/data_diri_view.php

      $session(['email'=>'ahmadkarimhh@gmail.com');

      $jsonResult=array();

      $address=DB::table('order')
      ->where('patient_code','=',session('email'))
      ->select('other')
      -get();

      

      foreach ($address as $key => $value) {
        # code...
        $other=explode("#",$value->other);
        $jsonResult[$i]["patient_name"]=$other[0];
        $jsonResult[$i]["Birth"]=$other[1];
        $jsonResult[$i]["gender"]=$other[2];
        $jsonResult[$i]["address"]=$other[3];
        $jsonResult[$i]["city_code"]=$other[4];
        $jsonResult[$i]["phone"]=$other[5];
        if(!empty($other[6])){
          $jsonResult[$i]["service"]=$other[6];
        }
        if(!empty($other[7])){
          $jsonResult[$i]["test_date"]=$other[7];
        }
      }

      return Response()->json(array(
                  'error'     =>  false,
                  'stores'    =>  $jsonResult),
                  200
          );


    }
}
