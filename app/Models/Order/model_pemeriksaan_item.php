<?php

namespace App\Models\Order;
use DB;
use Illuminate\Database\Eloquent\Model;
use Cart;
class model_pemeriksaan_item extends Model
{
    //
    public static function getData(){
      //name: karim
      //date create: 5 jan 16
      //routes: Route::get('order/pemeriksaan/package','OrderController@pemeriksaan_package');
      //controller: OrderController@pemeriksaan_package
            $tableIds = DB::table('item_master')
            //->join('item','item.master_code','=','item_master.master_code')
            ->select(DB::raw('master_code'),'item_master.name','item_master.preparation')->get();
                $jsonResult = array();
                $jsonResult2 = array();


                  # code...

                for($i = 0;$i < count($tableIds);$i++)
                {

                  $jsonResult[$i]["master_code"] = $tableIds[$i]->master_code;
                  $jsonResult[$i]["name"] = $tableIds[$i]->name;
                  $jsonResult[$i]["preparation"] = $tableIds[$i]->preparation;
                  $flag=0;
                  if (count(Cart::instance('cart')->content())==0){
                    $jsonResult[$i]["button"] = 'add';
                  }
                  foreach (Cart::instance('cart')->content() as  $value) {
                      if($value->id== $tableIds[$i]->master_code){
                        $flag=1;
                        $jsonResult[$i]["button"] = 'remove';
                      }
                  }
                  if($flag!=1){
                    $jsonResult[$i]["button"] = 'add';
                  }
               }

                 return Response()->json(array(
                             'error'     =>  false,
                             'stores'    =>  $jsonResult),
                             200
                     );
  }
}
