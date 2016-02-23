<?php

namespace App\Models\Other;

use Illuminate\Database\Eloquent\Model;
use DB;
class model_deskripsi extends Model
{
    //
    public static function getData($id){
      //name: karim
      //date create: 18 feb 16
      //routes: Route::get('deskripsi/{id}','OtherController@deskripsi');
      //model: Model/Ohther/model_deskripsi.blade.php
      //if(substr($id,0,2)=='PM'){
      $jsonResult = array();
      $jsonResult2 = array();
        $tableIds_package = DB::table('package_master')
        ->where("name",'like',"%$id%")
        ->get();


             for($i = 0;$i < count($tableIds_package);$i++)
             {

                  $jsonResult[$i]["package_master_code"] = $tableIds_package[$i]->package_master_code;
                  $jsonResult[$i]["name"] = $tableIds_package[$i]->name;
                  $jsonResult[$i]["test_type"] = $tableIds_package[$i]->test_type;
                  $jsonResult[$i]["test_result"] = $tableIds_package[$i]->test_result;
                  $jsonResult[$i]["description"] = $tableIds_package[$i]->description;
                  $id_package = $tableIds_package[$i]->package_master_code;

                 $table2_package = DB::table('panel')
                 ->join('package_detail','package_detail.panel_code','=','panel.panel_code')
                 ->where('package_detail.package_code','=',$id_package)
                 ->select(DB::raw('distinct(panel.name)'),'panel.panel_code')
                 ->get();

                 for($k = 0;$k < count($table2_package);$k++){

                   $item_package=DB::table('package_detail')
                  ->join('item_master','item_master.master_code','=','package_detail.item_code')
                  ->where("package_detail.panel_code",'=',$table2_package[$k]->panel_code)
                  ->where("package_detail.package_code",'=',$id_package)
                  ->select(DB::raw('distinct(item_master.name)'),'item_master.preparation')
                   ->get();


                    $jsonResult[$i]["panel"][$k]["panel_code"]=$table2_package[$k]->panel_code;
                    $jsonResult[$i]["panel"][$k]["panel_name"]=$table2_package[$k]->name;
                    $jsonResult[$i]["panel"][$k]["item"]=$item_package;
                  }
             }
      //}elseif(substr($id,0,2)=='P0'){
        $tableIds_panel = DB::table('panel')
        ->where('name','like',"%$id%")
        ->get();
      //  return $tableIds_panel;

            //$jsonResult = array();
          //  $jsonResult2 = array();

             for($i = 0;$i < count($tableIds_panel);$i++)
             {


                 $jsonResult[$i]["panel_code"] = $tableIds_panel[$i]->panel_code;
                 $jsonResult[$i]["name"] = $tableIds_panel[$i]->name;
                 $jsonResult[$i]["test_type"] = $tableIds_panel[$i]->test_type;
                 $jsonResult[$i]["test_result"] = $tableIds_panel[$i]->test_result;
                 $jsonResult[$i]["description"] = $tableIds_panel[$i]->description;
                 $id_panel = $tableIds_panel[$i]->panel_code;

                 $table2_panel = DB::table('panel')
                 ->join('panel_detail','panel_detail.panel_code','=','panel.panel_code')
                 ->join('item_master','item_master.master_code','=','panel_detail.item_code')
                 ->where('panel_detail.panel_code','=',$id_panel)
                 ->select(DB::raw('item_master.name'),'item_master.master_code','item_master.preparation')
                 ->get();

                 $jsonResult[$i]["item"]=$table2_panel;
             }


           //}
      //}elseif(substr($id,0,1)=='M'){
        $tableIds_item = DB::table('item_master')
        ->where('name','like',"$id")
        ->get();
            //$jsonResult = array();
          //  $jsonResult2 = array();

             for($i = 0;$i < count($tableIds_item);$i++)
             {
                 $jsonResult[$i]["master_code"] = $tableIds_item[$i]->master_code;
                 $jsonResult[$i]["name"] = $tableIds_item[$i]->name;
                 $jsonResult[$i]["preparation"] = $tableIds_item[$i]->preparation;
                 $jsonResult[$i]["test_type"] = $tableIds_item[$i]->test_type;
                 $jsonResult[$i]["test_result"] = $tableIds_item[$i]->test_result;
                 $jsonResult[$i]["description"] = $tableIds_item[$i]->description;
             }
      //}



      return Response()->json(array(
                  'error'     =>  false,
                  'stores'    =>  $jsonResult),
                  200
          );


    }
}
