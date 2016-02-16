<?php

namespace App\Models\Order;
use DB;
use Illuminate\Database\Eloquent\Model;

class model_riwayat extends Model
{
    public static function getData(){
      //name: karim
      //date create: 5 jan 16
      //routes: Route::get('order/riwayat','OrderController@riwayat');
      //controller: OrderController@riwayat

         session(['email' => 'ahmadkarimhh@gmail.com']);
        $tableIds = DB::table('orders')
    		->join('company','company.company_code','=','orders.company_code')
    		->select('orders_code','date','patient_code','grand_total','company.name','other','orders.status')
    		->orderby('date','desc')
    		->where('orders.patient_code','=',session('email'))
    		->get();

        $jsonResult = array();

         for($i = 0;$i < count($tableIds);$i++)
         {
             $jsonResult[$i]["orders_code"] = $tableIds[$i]->orders_code;
             $jsonResult[$i]["patient_code"] = $tableIds[$i]->patient_code;
             $jsonResult[$i]["date"] = $tableIds[$i]->date;
             $jsonResult[$i]["grand_total"] = 'Rp '. number_format($tableIds[$i]->grand_total , 2, ',', '.');
             $jsonResult[$i]["company.name"] = $tableIds[$i]->name;
             $jsonResult[$i]["other"] = $tableIds[$i]->other;

             //isi status pemesanan
             $status="";
             if($tableIds[$i]->status=="0"){
               $status="Pending";
             }elseif($tableIds[$i]=="1"){
               $status="Sukses";
             }else{
               $status="Dibatalkan";
             }

             $jsonResult[$i]["orders.status"] = $status;
         }
         return Response()->json(array(
                     'error'     =>  false,
                     'stores'    =>  $jsonResult),
                     200
             );


           }
}
