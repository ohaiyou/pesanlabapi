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
    		->join('lab','lab.lab_code','=','orders.company_code')
        ->join('company','company.company_code','=','lab.company_code')
    		->select('orders_code',DB::raw("DATE_FORMAT(date,'%d %b %Y %h:%i %p') as date"),'patient_code as user_email','grand_total','lab.name as lab_name','other','orders.status')
    		->orderby('date','desc')
    		->where('orders.patient_code','=',session('email'))
    		->get();

        $jsonResult = array();

         for($i = 0;$i < count($tableIds);$i++)
         {
           $jsonResult[$i]["orders_code"] = $tableIds[$i]->orders_code;
           $jsonResult[$i]["user_email"] = $tableIds[$i]->user_email;
           $jsonResult[$i]["date"] = $tableIds[$i]->date;
           $jsonResult[$i]["grand_total"] = 'Rp '. number_format($tableIds[$i]->grand_total , 2, ',', '.');
           $jsonResult[$i]["lab_name"] = $tableIds[$i]->lab_name;
        //   $jsonResult[$i]["other"] = $tableIds[$i]->other;

             $other=explode("#",$tableIds[$i]->other);
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




             //isi status pemesanan
             $status="";
             if($tableIds[$i]->status=="0"){
               $status="Pending";
             }elseif($tableIds[$i]=="1"){
               $status="Sukses";
             }else{
               $status="Dibatalkan";
             }

             $jsonResult[$i]["orders_status"] = $status;
         }
         return Response()->json(array(
                     'error'     =>  false,
                     'stores'    =>  $jsonResult),
                     200
             );


           }
}
