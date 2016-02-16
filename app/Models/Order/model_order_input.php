<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use DB;
use Cart;
use Mail;

class model_order_input extends Model
{
    //
    public static function postData(){
      //name: karim
      //date create: 15 feb 16
      //routes: Route::get('order/input','OrderController@order_input');
      //controller: OrderController@orders_input
      if(count(Cart::instance('cart')->content())==0){
			     return Redirect('api/v1/order/pemeriksaan/panel');
		  }


      $lab_code=DB::table('lab')
      ->join('company','company.company_code','=','lab.company_code')
      ->where('lab.lab_code','=',session('lab_code'))
      ->select(DB::raw('lab.lab_code'),'lab.name','company.name as company_name','company.company_code','lab.address')
      ->get();
      foreach ($lab_code as $value) {
        # code...
        $lab_code=$value->lab_code;
        $lab_name=$value->name;
        $company_code=$value->company_code;
        $company_name=$value->company_name;
        $lab_address=$value->address;

      }


      //generate order code
        $length = 7;
		    $characters = '0123456789';
		    $charactersLength = strlen($characters);
		    $randomString = "";
		    $namalab="";
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }

      DB::beginTransaction();


      $data_panel=array();
      $data_item=array();
      $data_package=array();

      $subtotal=0;
      $grand_total=0;

      $idx_package=0;
      $idx_panel=0;
      $idx_item=0;

      $total_lab=0;
      $total_lab_normal=0;





        $total_lab=0;
        $total_lab_normal=0;

        $data_item=[];
        $data_panel=[];
        $data_package=[];
        $data_lab=[];

        //return session('data_diri');
        $datadiri=explode("#",session('data_diri'));
        $result[0]["data_diri"][0]["nama_pasien"]=$datadiri[0];
        $result[0]["data_diri"][0]["bod"]=$datadiri[1];
        $result[0]["data_diri"][0]["gender"]=$datadiri[2];
        $result[0]["data_diri"][0]["alamat"]=$datadiri[3];
        $result[0]["data_diri"][0]["cit_code"]=$datadiri[4];
        $result[0]["data_diri"][0]["telp"]=$datadiri[5];
        $result[0]["data_diri"][0]["layanan"]=$datadiri[6];


        $result[0]["code_lab"]=$lab_code;
        $result[0]["nama_lab"]=$lab_name;
        $result[0]["address_lab"]=$lab_address;

      $idx_package=0;
      $idx_panel=0;
      $idx_item=0;

      session(['email'=>'ahmadkarimhh@gmail.com']);

      try {
          DB::table('orders')->insert(array(
            'orders_code' => $randomString,
            'date' => DB::raw('NOW()') ,
            'patient_code' => session('email') ,
            'valid_until' =>  DB::raw('NOW()'),
            'grand_total' => 0,//Cart::instance('orders')->total() ,
            'company_code' =>session('lab_code') ,
            'doctor' => '' ,
            'other' => session('data_diri') ,
            'doctor_phone' =>'' ,
            'online' => true ,
            'status' =>'0'
          ));
        } catch (Exception $e) {
          DB::rollback();
        }
      foreach (Cart::instance('cart')->Content() as $key=>$value) {

        # code...
        $jenis=[];
        $panel=[];
        $package=[];
        $querypanelitem=[];
        $packageitem=[];

        $lab=array();

        //isi item dari panel
        $total_item=0;
        $total_normal=0;


        if(substr($value->id,0,2)=='P0'){

            $tableIds = DB::table('panel')
            ->join('panel_detail2','panel_detail2.panel_code','=','panel.panel_code')
            ->join('company','company.company_code','=','panel_detail2.company_code')
            ->where("panel.panel_code",'=',$value->id)
            ->where("panel_detail2.company_code",'=',$company_code)
            ->select('panel.name','panel_detail2.panel_codepk','panel_detail2.company_code','company.name as company','panel.panel_code')
            ->orderby('company')
            ->orderby('name')
            ->get();

               $querypanelitem = array();

                for($i = 0;$i < count($tableIds);$i++)
                {

                    $id = $tableIds[$i]->panel_codepk;

                    $table2 = DB::table('panel')
                    ->join('panel_detail','panel_detail.panel_code','=','panel.panel_code')
                    ->join('item_master','item_master.master_code','=','panel_detail.item_code')
                    ->join('item','item.master_code','=','item_master.master_code')
                    ->where('panel_detail.panel_code','=',$value->id)
                    ->where('item.company_code','=',$company_code)
                    ->select(DB::raw('item.name'),'item_master.master_code','item_master.preparation','item.company_code','price_disc','item.price')
                    ->get();


                    foreach ($table2 as $value_panel) {
                      # code...
                      //untuk menghitung total panel
                      $total_item+=$value_panel->price_disc;
                      //menghitung haga normal
                      $total_normal+=$value_panel->price;


                    //insert into trans_anel tabletry{
                    try{
                      DB::table('trans_panel_detail')->insert(array(
                        'panel_code' =>$tableIds[$i]->panel_code,
                        'item_code' => $value_panel->master_code,
                        'orders_code'=> $randomString
                      ));

                    } catch (Exception $e) {
                      DB::rollback();
                    }

                    }
                    //untuk menghitung harga totl lab
                    $total_lab+=$total_item;
                    //untuk menghitung harga toal harga normal lab_name
                    $total_lab_normal+=$total_normal;




                    $result[$i]["panel_detail"][$idx_panel]["panel_name"]=$tableIds[$i]->name;
                    $result[$i]["panel_detail"][$idx_panel]["panel_code"]=$tableIds[$i]->panel_codepk;
                    $result[$i]["panel_detail"][$idx_panel]["cmpany_name"]=$tableIds[$i]->company;
                    $result[$i]["panel_detail"][$idx_panel]["company_code"]=$tableIds[$i]->company_code;
                    $result[$i]["panel_detail"][$idx_panel]["item"]=$table2;
                    $result[$i]["panel_detail"][$idx_panel]["harga_normal"]=$total_normal;
                    $result[$i]["panel_detail"][$idx_panel]["harga_diskon"]=$total_item;
                    $result[$i]["panel_detail"][$idx_panel]["harga_hemat"]=$total_normal-$total_item;



                    //insert into order detail
                    try {
            					DB::table('orders_detail')->insert(array(
            						'item_codepk' => $tableIds[$i]->panel_codepk,
            						'orders_code' =>$randomString,
            						'name'=> $tableIds[$i]->name,
            						'price_disc'=>$total_item,
            						'disc'=>0,
            						'price'=>$total_normal,
            						'company_code'=>$lab_code
            					));
            				} catch (Exception $e) {
            					DB::rollback();
            				}










                }
                  $idx_panel+=1;
         }

          //isi item dari package
        if(substr($value->id,0,2)=='PM'){

          $row=Cart::instance('cart')->content();
          $tableIds = DB::table('package_master')
          ->join('package','package.package_master_code','=','package_master.package_master_code')
          ->join('company','company.company_code','=','package.company_code')
          ->select('package_master.name','package.package_code','package.package_master_code','company.name as company','company.company_code')
          ->where('package.package_master_code','=',$value->id)
          ->where('package.company_code','=',$company_code)
          ->orderby('company')
          ->orderby('name')
          ->get();


              $packageitem = array();
              $jsonResult2 = array();

               for($i = 0;$i < count($tableIds);$i++)
               {
                  $result[$i]["package_detail"][$idx_package]["package_code"]=$tableIds[$i]->package_code;
                  $result[$i]["package_detail"][$idx_package]["name"]=$tableIds[$i]->name;
                  $result[$i]["package_detail"][$idx_package]["company_name"]=$tableIds[$i]->name;
                  $result[$i]["package_detail"][$idx_package]["company_code"]=$tableIds[$i]->company_code;


                  //insert into order detail
                  try {
                    DB::table('orders_detail')->insert(array(
                      'item_codepk' => $tableIds[$i]->package_code,
                      'orders_code' =>$randomString,
                      'name'=> $tableIds[$i]->name,
                      'price_disc'=>0,
                      'disc'=>0,
                      'price'=>0,
                      'company_code'=>$lab_code
                    ));
                  } catch (Exception $e) {
                    DB::rollback();
                  }



                   $id = $tableIds[$i]->package_master_code;

                   $table2 = DB::table('panel')
                   ->join('package_detail','package_detail.panel_code','=','panel.panel_code')
                   ->where('package_detail.package_code','=',$id)
                   ->select(DB::raw('distinct(panel.name)'),'panel.panel_code')
                   ->get();
                   $total_item=0;
                   $total_normal=0;
                   for($k = 0;$k < count($table2);$k++){


                     $item=DB::table('package_detail')
                    ->join('item_master','item_master.master_code','=','package_detail.item_code')
                    ->join('item','item.master_code','=','item_master.master_code')
                    ->where("package_detail.panel_code",'=',$table2[$k]->panel_code)
                    ->where("package_detail.package_code",'=',$id)
                    ->where("item.company_code",'=',$company_code)
                    ->select(DB::raw('item.name'),'item_master.preparation','item.price_disc','item.price','status')
                    ->get();

                    $result[$i]["package_detail"][$idx_package]["panel"][$k]["panel_code"]=$table2[$k]->panel_code;
                    $result[$i]["package_detail"][$idx_package]["panel"][$k]["panel_name"]=$table2[$k]->name;
                    $result[$i]["package_detail"][$idx_package]["panel"][$k]["item"]=$item;



                      foreach ($item as $value_package) {
                        # code...
                        //untuk menghitung total panel
                        $total_item+=$value_package->price_disc;
                        //menghitung haga normal
                        $total_normal+=$value_package->price;

                        //insert into trans_package_detail
                        try{
            							DB::table('trans_package_detail')->insert(array(
            								'package_code' => $tableIds[$i]->package_code,
            								'panel_code' =>$table2[$k]->panel_code,
            								'item_code'=> $value_package->name,
            								'status'=> $value_package->status,
            								'orders_code'=> $randomString

            							));
            						} catch (Exception $e) {
            							DB::rollback();
            						}


                      }

                    }
                    //untuk menghitung harga totl lab
                    $total_lab+=$total_item;
                    //untuk menghitung harga toal harga normal lab_name
                    $total_lab_normal+=$total_normal;


                 }

                 $result[$i]["package_detail"][$idx_package]["harga_normal"]=$total_normal;
                 $result[$i]["package_detail"][$idx_package]["harga_diskon"]=$total_item;
                 $result[$i]["package_detail"][$idx_package]["harga_hemat"]=$total_normal-$total_item;


                 //return $packageitem;
                 $idx_package+=1;
          }




        //isi data master
        if(substr($value->id,0,1)=='M'){
          $jenisitem=DB::table('item_master')
          ->join('item','item.master_code','=','item_master.master_code')
          ->join('company','company.company_code','=','item.company_code')
          ->where('item.master_code','=',"$value->id")
          ->where('item.company_code','=',$company_code)
          ->select('item.item_code as code','item.name','price_disc as harga_diskon','price as harga_normal','disc','item_master.master_code','company.company_code',DB::raw('(item.price-item.price_disc) as harga_hemat'),'item.group_item')
          ->orderby('company.company_code')
          ->orderby('name')
          ->get();


          foreach ($jenisitem as $value_item) {
            # code...
            //untuk menghitung total panel
            $total_item=0;
            $total_normal=0;
            $total_item+=$value_item->harga_diskon;
            $total_normal+=$value_item->harga_normal;

            //insert into order detail
            try {
              DB::table('orders_detail')->insert(array(
                'item_codepk' => $value_item->code,
                'orders_code' =>$randomString,
                'name'=> $value_item->name,
                'price_disc'=>$value_item->harga_diskon,
                'disc'=>$value_item->disc,
                'price'=>$value_item->harga_normal,
                'company_code'=>$lab_code
              ));
            } catch (Exception $e) {
              DB::rollback();
            }

            //insert into trans panel item
            try{
					 	DB::table('trans_item')->insert(array(
							'item_code' =>$value_item->code,
							'name' =>$value_item->name,
							'disc'=> $value_item->disc,
							'price'=> $value_item->harga_normal,
							'group_item'=> $value_item->group_item,
							'company_code'=> $lab_code,
							'master_code'=> $value_item->master_code,
							'price_disc'=> $value_item->harga_diskon,
							'orders_code'=>$randomString,
							'foreign_key'=>$value_item->code
						));
					} catch (Exception $e) {
					DB::rollback();
				}

          }
          //untuk menghitung harga totl lab
            $total_lab+=$total_item;
            //untuk menghitung harga toal harga normal lab_name
            $total_lab_normal+=$total_normal;
            $result[$i]["item_detail"][$idx_item]["item"]=$jenisitem;
            $idx_item+=1;

        }



}//end 1st foreach
  //harga total lab
$result[$i]["Total_Harga_Normal"]=$total_lab_normal;
$result[$i]["Total_Harga_Diskon"]=$total_lab;
$result[$i]["Total_Harga_Hemat"]=$total_lab_normal-$total_lab;


DB::commit();




			//send mail
			//return View::make('orders.emailorders',$array);
			$subject='Pesanan Pemeriksaan Lab '.session('name'). ' Order No. '.$randomString;
			$emails = ['order@pesanlab.com',session('email')];
		/*	Mail::send('orders.emailorders',$result, function($m) use ($emails,$subject) {
			   $m->to($emails);
			   $m->subject($subject);
			});
      */



      return Response()->json(array(
                  'error'     =>  false,
                  'stores'    =>  $result),
                  200
          );











    }
}
