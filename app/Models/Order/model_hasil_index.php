<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use DB;
use File;

class model_hasil_index extends Model
{
    //
    public static function getData(){
      //name: karim
      //date create: 15 feb 16
      //routes: Route::get('hasil','OrderController@hasil_index');
      //controller: OrderController@hasil_index
      session(['email'=>'ahmadkarimhh@gmail.com']);
      if(session('email')==""){
			//return Redirect::to('restricted/result');
		}

		//
		//tampilkan list product
		$hasil=DB::table('orders')
		->join('company','company.company_code','=','orders.company_code')
    ->join("lab","lab.company_code",'=','company.company_code')
    ->join('patient','patient.email','=','orders.patient_code')
		->where('orders.patient_code','=',session('email'))
		->select('orders_code','date','patient.patient_code','grand_total','lab.name','other','orders.status',
    DB::raw("CONCAT('Hasil-', patient.name ,'-',orders.orders_code,'.pdf') as file_name"))
		->orderby('date','desc');
		//->get();


		$patient=DB::table('patient')
		->where('email','=',session('email'))
    ->select('name','email',DB::raw("DATE_FORMAT(regis_date,'%d %b %Y %h:%i %p') as register_date"))
		->get();


		/*$count=DB::table('orders')
		->where('patient_code','=',Session::get('email'))
		->select(DB::raw('count(patient_code) as count'))
		->get();*/

		$orders=DB::table('orders')
		->where('patient_code','=',session('email'))
		->select('orders_code')
		->get();


    $result=array();





		////////////////////////////////////////////////////////////////
		$log_files = File::glob('pdf/*.pdf');
		$pdf=[
			'pdf'=>$log_files
		];




		$arr_pdf=array();

		if ($log_files == true)
		{
			foreach ($log_files as $value) {
				//return substr($value,-11,7);
				$sub=substr($value,-11,7);
				foreach ($orders as $value2){
					 if(str_contains($sub,$value2->orders_code)){
					   array_push($arr_pdf, array('code' => $value2->orders_code));
					 }
				}
			}
		}
		$hasil=$hasil->wherein('orders.orders_code',$arr_pdf);
		$hasil=$hasil->get();
		//////////////////////




		$count=count($hasil);
    $result[0]["user"]=$patient;
    $result[0]["jumlah"]=$count;



//->select('orders_code','date','patient.patient_code','grand_total','company.name','other','orders.status',

    for ($i=0; $i < count($hasil) ; $i++) {
      # code...
    //  $result[$i]["hasil"]=$hasil;
      $result[0]["result"][$i]["orders_code"]=$hasil[$i]->orders_code;
      $result[0]["result"][$i]["orders_date"]=$hasil[$i]->date;
      $result[0]["result"][$i]["email_user"]=$hasil[$i]->patient_code;
      $result[0]["result"][$i]["grand_total"]=$hasil[$i]->grand_total;
      $result[0]["result"][$i]["lab_name"]=$hasil[$i]->name;
      $result[0]["result"][$i]["orders_status"]=$hasil[$i]->status;
      $other=explode("#",$hasil[$i]->other);
      $result[0]["result"][$i]["patient"][0]["patient_name"]=$other[0];
      $result[0]["result"][$i]["patient"][0]["Birth"]=$other[1];
      $result[0]["result"][$i]["patient"][0]["address"]=$other[3];
      $result[0]["result"][$i]["patient"][0]["gender"]=$other[2];
      $result[0]["result"][$i]["patient"][0]["city_code"]=$other[4];
      $result[0]["result"][$i]["patient"][0]["phone"]=$other[5];
      if(!empty($other[6])){
        $result[0]["result"][$i]["patient"][0]["service"]=$other[6];
      }
      if(!empty($other[7])){
        $result[0]["result"][$i]["patient"][0]["test_date"]=$other[7];
      }
    }


    return Response()->json(array(
                'error'     =>  false,
                'stores'    =>  $result),
                200
        );



		$hasil=[
			'hasil'=>$hasil,'patient'=>$patient,'count'=>$count
		];
		return View::make('result.index',$hasil);

    }
}
