<?php

namespace App\Models\Order;
use Cart;
use DB;
use Illuminate\Database\Eloquent\Model;

class model_konfirmasi extends Model
{
    //

    public static function getData($request){

      $currency=  app('App\Http\Controllers\FunctionController');
      $lab_code=DB::table('lab')
      ->join('company','company.company_code','=','lab.company_code')
      ->where('lab.lab_code','=',$request->lab_code)
      ->select(DB::raw('lab.lab_code'),'lab.name','company.name as company_name','company.company_code','lab.address')
      ->get();
      foreach ($lab_code as $value) {
        # code...
        $lab_code=$value->lab_code;
        $lab_name=$value->name;
        $company_code=$value->company_code;
        $company_name=$value->company_name;
        $lab_address=$value->address;

        session(['lab_code'=>$lab_code]);
      }




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
            ->select('panel.name','panel_detail2.panel_codepk','panel_detail2.company_code','company.name as company')
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

                    }
                    //untuk menghitung harga totl lab
                    $total_lab+=$total_item;
                    //untuk menghitung harga toal harga normal lab_name
                    $total_lab_normal+=$total_normal;



                    $result[$i]["panel_detail"][$idx_panel]["panel_name"]=$tableIds[$i]->name;
                    $result[$i]["panel_detail"][$idx_panel]["panel_code"]=$tableIds[$i]->panel_codepk;
                    $result[$i]["panel_detail"][$idx_panel]["cmpany_name"]=$tableIds[$i]->company;
                    $result[$i]["panel_detail"][$idx_panel]["company_code"]=$tableIds[$i]->company_code;
                    //$result[$i]["panel_detail"][$idx_panel]["item"]=$table2;
                    //tampilkan item
                    for ($k=0; $k < count($table2); $k++) {
                      # code...
                      $result[$i]["panel_detail"][$idx_panel]["item"][$k]["name"]=$table2[$k]->name;
                      $result[$i]["panel_detail"][$idx_panel]["item"][$k]["preparation"]=$table2[$k]->preparation;
                      $result[$i]["panel_detail"][$idx_panel]["item"][$k]["harga"]=
                      $currency->getcurrency($table2[$k]->price);
                      $result[$i]["panel_detail"][$idx_panel]["item"][$k]["harga_diskon"]=
                      $currency->getcurrency($table2[$k]->price_disc);
                    }
                    $result[$i]["panel_detail"][$idx_panel]["harga_normal"]=$currency->getcurrency($total_normal);
                    $result[$i]["panel_detail"][$idx_panel]["harga_diskon"]=$currency->getcurrency($total_item);
                    $result[$i]["panel_detail"][$idx_panel]["harga_hemat"]=$currency->getcurrency($total_normal-$total_item);




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
                    ->select(DB::raw('item.name'),'item_master.preparation','item.price_disc','item.price')
                    ->get();

                    $result[$i]["package_detail"][$idx_package]["panel"][$k]["panel_code"]=$table2[$k]->panel_code;
                    $result[$i]["package_detail"][$idx_package]["panel"][$k]["panel_name"]=$table2[$k]->name;
                    //$result[$i]["package_detail"][$idx_package]["panel"][$k]["item"]=$item;

                    //tampilkan item
                    for ($l=0; $l < count($item); $l++) {
                      # code...
                      $result[$i]["package_detail"][$idx_package]["panel"][$k]["item"][$l]["name"]=$item[$l]->name;
                      $result[$i]["package_detail"][$idx_package]["panel"][$k]["item"][$l]["preparation"]=$item[$l]->preparation;
                      $result[$i]["package_detail"][$idx_package]["panel"][$k]["item"][$l]["harga"]=
                      $currency->getcurrency($item[$l]->price);
                      $result[$i]["package_detail"][$idx_package]["panel"][$k]["item"][$l]["harga_diskon"]=$currency->getcurrency($item[$l]->price_disc);
                    }

                      foreach ($item as $value_panel) {
                        # code...
                        //untuk menghitung total panel
                        $total_item+=$value_panel->price_disc;
                        //menghitung haga normal
                        $total_normal+=$value_panel->price;

                      }

                    }
                    //untuk menghitung harga totl lab
                    $total_lab+=$total_item;
                    //untuk menghitung harga toal harga normal lab_name
                    $total_lab_normal+=$total_normal;


                 }


                 $result[$i]["package_detail"][$idx_package]["harga_normal"]=$currency->getcurrency($total_normal);
                 $result[$i]["package_detail"][$idx_package]["harga_diskon"]=$currency->getcurrency($total_item);
                 $result[$i]["package_detail"][$idx_package]["harga_hemat"]=$currency->getcurrency($total_normal-$total_item);


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
          ->select('item.item_code as code','item.name','price_disc as harga_diskon','price as harga_normal','disc','item_master.master_code','company.company_code',DB::raw('(item.price-item.price_disc) as harga_hemat'))
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

          }
          //untuk menghitung harga totl lab
            $total_lab+=$total_item;
            //untuk menghitung harga toal harga normal lab_name
            $total_lab_normal+=$total_normal;
            //$result[$i]["item_detail"][$idx_item]["item"]=$jenisitem;
            //tampilkan item

              for ($j=0; $j < count($jenisitem) ; $j++) {
                # code...
                $result[0]["item_detail"][$idx_item]["item"][$j]["code"]=$jenisitem[$j]->code;
                $result[0]["item_detail"][$idx_item]["item"][$j]["name"]=$jenisitem[$j]->name;
                $result[0]["item_detail"][$idx_item]["item"][$j]["harga_diskon"]=$currency->getcurrency($total_item);
                $result[0]["item_detail"][$idx_item]["item"][$j]["harga_normal"]=$currency->getcurrency($total_normal);
                $result[0]["item_detail"][$idx_item]["item"][$j]["harga_hemat"]=$currency->getcurrency($total_normal-$total_item);
                $idx_item+=1;
              }




        }
  //harga total lab


}//end 1st foreach
$result[$i]["Total_Harga_Normal"]=$currency->getcurrency($total_lab_normal);
$result[$i]["Total_Harga_Diskon"]=$currency->getcurrency($total_lab);
$result[$i]["Total_Harga_Hemat"]=$currency->getcurrency($total_lab_normal-$total_lab);

return Response()->json(array(
            'error'     =>  false,
            'stores'    =>  $result),
            200
    );







    }
}
