<?php

namespace App\Models\Order;
use DB;
use Illuminate\Database\Eloquent\Model;
use Cart;
class model_pemeriksaan_package extends Model
{
    //
    //
    public static function getData(){
      //name: karim
      //date create: 5 jan 16
      //routes: Route::get('order/pemeriksaan/package','OrderController@pemeriksaan_package');
      //controller: OrderController@pemeriksaan_package
            $tableIds = DB::table('package_master')->select('name','package_master_code')->get();
                $jsonResult = array();
                $jsonResult2 = array();

                 for($i = 0;$i < count($tableIds);$i++)
                 {


                     $jsonResult[$i]["package_master_code"] = $tableIds[$i]->package_master_code;
                      $jsonResult[$i]["name"] = $tableIds[$i]->name;
                      //check cart
                      $flag=0;
                      if (count(Cart::instance('cart')->content())==0){
                        $jsonResult[$i]["button"] = 'add';
                      }
                      foreach (Cart::instance('cart')->content() as  $value) {
                          if($value->id== $tableIds[$i]->package_master_code){
                            $flag=1;
                            $jsonResult[$i]["button"] = 'remove';
                          }
                      }
                      if($flag!=1){
                        $jsonResult[$i]["button"] = 'add';
                      }
                      //close check cart

                     $id = $tableIds[$i]->package_master_code;

                     $table2 = DB::table('panel')
                     ->join('package_detail','package_detail.panel_code','=','panel.panel_code')
                     ->where('package_detail.package_code','=',$id)
                     ->select(DB::raw('distinct(panel.name)'),'panel.panel_code')
                     ->get();

                     for($k = 0;$k < count($table2);$k++){

                       $item=DB::table('package_detail')
                      ->join('item_master','item_master.master_code','=','package_detail.item_code')
                      ->where("package_detail.panel_code",'=',$table2[$k]->panel_code)
                      ->where("package_detail.package_code",'=',$id)
                      ->select(DB::raw('distinct(item_master.name)'),'item_master.preparation')
                       ->get();


                        $jsonResult[$i]["panel"][$k]["panel_code"]=$table2[$k]->panel_code;
                        $jsonResult[$i]["panel"][$k]["panel_name"]=$table2[$k]->name;
                        $jsonResult[$i]["panel"][$k]["item"]=$item;


                      }

                 }

                 return Response()->json(array(
                             'error'     =>  false,
                             'stores'    =>  $jsonResult),
                             200
                     );
  }
}
