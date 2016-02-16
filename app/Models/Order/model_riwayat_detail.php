<?php

namespace App\Models\Order;
use DB;
use Illuminate\Database\Eloquent\Model;

class model_riwayat_detail extends Model
{
    //
    public static function getData($id){
      //name: karim
      //date create: 5 jan 16
      //routes: Route::get('order/riwayat','OrderController@riwayat');
      //controller: OrderController@riwayat

        session(['email' => 'ahmadkarimhh@gmail.com']);
        $order = DB::table('orders')
        		->join('company','company.company_code','=','orders.company_code')
        		->join('patient','orders.patient_code','=','patient.email')
        		->select('orders.orders_code',DB::raw("DATE_FORMAT(date,'%d-%b-%Y %H:%i') as date"),'orders.patient_code','grand_total','company.name as company_name','other','company.company_code','orders.company_code')
        		->where('orders.orders_code','=',"$id")
        		->where('orders.patient_code','=',session('email'))
        		->get();

        $order_detail=DB::table('orders')
        ->join('orders_detail','orders_detail.orders_code','=','orders.orders_code')
        ->where('orders_detail.orders_code','=',$id)
        ->select('name','item_codepk','orders.company_code','orders.orders_code')
        ->get();

         for($i = 0;$i < count($order);$i++)
         {
             $jsonResult[$i]["orders_code"] = $order[$i]->orders_code;
             $jsonResult[$i]["date"] = $order[$i]->date;
             $jsonResult[$i]["patient_code"] = $order[$i]->patient_code;
             $jsonResult[$i]["grand_total"] = 'Rp '. number_format($order[$i]->grand_total , 2, ',', '.');
             $jsonResult[$i]["company_name"] = $order[$i]->company_name;
             $jsonResult[$i]["company_code"] = $order[$i]->company_code;
             $jsonResult[$i]["other"] = $order[$i]->other;


             //for $order_detail
             for($k = 0;$k < count($order_detail);$k++){
                $jsonResult[$i]["pemeriksaan"][$k]["code"]=$order_detail[$k]->item_codepk;
                $jsonResult[$i]["pemeriksaan"][$k]["name"]=$order_detail[$k]->name;

                //untuk package
                $panel=DB::table('trans_package_detail')
            			->join("package",'package.package_master_code','=','trans_package_detail.package_code')
                  ->join("panel",'panel.panel_code','=','trans_package_detail.panel_code')
            			->where('trans_package_detail.orders_code','=',$order_detail[$k]->orders_code)
                  ->where('package.company_code','=',$order_detail[$k]->company_code)
                  ->where('package.package_code','=',$order_detail[$k]->item_codepk)
                  ->select(DB::raw('distinct(panel.panel_code)'),"package.package_code","panel.name")
                  ->groupby("package.package_code","panel.name")
                  ->orderby("panel.panel_code")
            			->get();

                //for package panel detail
                for($j = 0;$j < count($panel);$j++){
                  //untuk package
                  $jsonResult[$i]["pemeriksaan"][$k]["detail"][$j]["panel"]=$panel[$j]->panel_code;
                  $jsonResult[$i]["pemeriksaan"][$k]["detail"][$j]["panel_name"]=$panel[$j]->name;

                  //get item in package
                  $item_package=DB::table('trans_package_detail')
                  ->join("item_master",'item_master.master_code','=','trans_package_detail.item_code')
                  ->join("package",'package.package_master_code','=','trans_package_detail.package_code')
                  ->where("trans_package_detail.panel_code",'=',$panel[$j]->panel_code)
                  ->where("trans_package_detail.orders_code",'=',$id)
                  ->where("package.package_code",'=',$order_detail[$k]->item_codepk)
                  ->select("item_master.name")
                  ->get();
                  //for package item
                  for ($l=0; $l < count($item_package) ; $l++) {

                      $jsonResult[$i]["pemeriksaan"][$k]["detail"][$j]["item"][$l]["nama_item"]=$item_package[$l]->name;


                  }
                }


                //untuk panel
                //get item in package
                //untuk package
                $panel_item=DB::table('trans_panel_detail')
                  ->join("item_master",'item_master.master_code','=','trans_panel_detail.item_code')
                  ->join("panel",'panel.panel_code','=','trans_panel_detail.panel_code')
                  ->join("panel_detail2",'panel_detail2.panel_code','=','trans_panel_detail.panel_code')
                  ->where('trans_panel_detail.orders_code','=',$order_detail[$k]->orders_code)
                  ->where('panel_detail2.panel_codepk','=',$order_detail[$k]->item_codepk)
                  ->select("item_master.name")
                  ->orderby("panel.panel_code",'panel.name')
                  ->get();

                  for($j = 0;$j < count($panel_item);$j++){

                     $jsonResult[$i]["pemeriksaan"][$k]["item"][$j]["item_name"]=$panel_item[$j]->name;

                    }
              }
         }
         return Response()->json(array(
                     'error'     =>  false,
                     'stores'    =>  $jsonResult),
                     200
             );
}
}
