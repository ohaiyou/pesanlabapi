<?php

namespace App\Models\Order;
use DB;
use Illuminate\Database\Eloquent\Model;
use Cart;
class model_pemeriksaan_panel extends Model
{
    //
    public static function getData(){
      //name: karim
      //date create: 5 jan 16
      //routes: Route::get('order/pemeriksaan/package','OrderController@pemeriksaan_package');
      //controller: OrderController@pemeriksaan_package
            $tableIds = DB::table('panel')
            ->where('panel_code','<>','P000')
            ->select('name','panel_code')->get();
                $jsonResult = array();
                $jsonResult2 = array();

                 for($i = 0;$i < count($tableIds);$i++)
                 {


                     $jsonResult[$i]["panel_code"] = $tableIds[$i]->panel_code;
                     $jsonResult[$i]["name"] = $tableIds[$i]->name;

                     //check cart
                     $flag=0;
                     if (count(Cart::instance('cart')->content())==0){
                       $jsonResult[$i]["button"] = 'add';
                     }
                     foreach (Cart::instance('cart')->content() as  $value) {
                         if($value->id== $tableIds[$i]->panel_code){
                           $flag=1;
                           $jsonResult[$i]["button"] = 'remove';
                         }
                     }
                     if($flag!=1){
                       $jsonResult[$i]["button"] = 'add';
                     }
                     //close check cart

                     $id = $tableIds[$i]->panel_code;

                     $table2 = DB::table('panel')
                     ->join('panel_detail','panel_detail.panel_code','=','panel.panel_code')
                     ->join('item_master','item_master.master_code','=','panel_detail.item_code')
                     ->where('panel_detail.panel_code','=',$id)
                     ->select(DB::raw('item_master.name'),'item_master.master_code','item_master.preparation')
                     ->get();

                     $jsonResult[$i]["item"]=$table2;
                 }

                 return Response()->json(array(
                             'error'     =>  false,
                             'stores'    =>  $jsonResult),
                             200
                     );
            }
}
