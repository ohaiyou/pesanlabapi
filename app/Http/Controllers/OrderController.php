<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Order\model_pemeriksaan_package;
use App\Models\Order\model_pemeriksaan_panel;
use App\Models\Order\model_pemeriksaan_item;
use App\Models\Order\model_riwayat;
use App\Models\Order\model_riwayat_detail;
use App\Models\Order\model_cart_add;
use App\Models\Order\model_cart_get;
use App\Models\Order\model_cart_remove;
use App\Models\Order\model_pilih_lab;
use App\Models\Order\model_data_diri;
use App\Models\Order\model_data_diri_input;
use App\Models\Order\model_provinsi;
use App\Models\Order\model_kabupaten;
use App\Models\Order\model_kecamatan;
use App\Models\Order\model_konfirmasi;
use App\Models\Order\model_order_input;
use App\Models\Order\model_hasil_index;



class OrderController extends Controller
{
    //
    public function pemeriksaan_package(){
      //name: karim
      //date create: 5 feb 16
      //routes: Route::get('order/pemeriksaan/package','OrderController@pemeriksaan_package');
      //model: Model/Order/model_pemeriksaan_package.blade.php

      $data=model_pemeriksaan_package::getData();
      return $data;
    }

    public function pemeriksaan_panel(){
      //name: karim
      //date create: 5 feb 16
      //routes: Route::get('order/pemeriksaan/panel','OrderController@pemeriksaan_panel');
      //model: Model/Order/model_pemeriksaan_panel.php

      $data=model_pemeriksaan_panel::getData();
      return $data;
    }

    public function pemeriksaan_item(){
      //name: karim
      //date create: 5 feb 16
      //routes: Route::get('order/pemeriksaan/item','OrderController@pemeriksaan_item');
      //model: Model/Order/model_pemeriksaan_item.php

      $data=model_pemeriksaan_item::getData();
      return $data;
    }

    public function riwayat(Request $request){
      //name: karim
      //date create: 5 feb 16
      //routes: Route::get('order/riwayat','OrderController@riwayat');
      //model: Model/Order/riwayat.php
      $data=model_riwayat::getData();
      return $data;
    }
    public function riwayat_detail($id){
      //name: karim
      //date create: 5 feb 16
      //routes: Route::get('order/riwayat','OrderController@riwayat');
      //model: Model/Order/riwayat.php
      $data=model_riwayat_detail::getData($id);
      return $data;
    }
    public function cart_add(Request $request){
      //name: karim
      //date create: 9 feb 16
      //routes: Route::post('order/cart/add','OrderController@cart_add');
      //model: Model/Order/model_cart_add.php


      $data=model_cart_add::getData($request);
      return $data;
    }

    public function cart_get(){
      //name: karim
      //date create: 9 feb 16
      //routes: Route::post('order/cart/add','OrderController@cart_get');
      //model: Model/Order/model_cart_get.php
      $data=model_cart_get::getData();
      return $data;
    }

    public function cart_destroy()
    {
      //name: karim
      //date create: 9 Feb 16
      //routes: Route::post('order/cart/destroy','OrderController@cart_destroy');
      //model: -
      Cart::instance('cart')->destroy();
      Cart::instance('orders')->destroy();
    }

    public function cart_remove($id)
    {
      //name: karim
      //date create: 9 Feb 16
      //routes: Route::post('order/cart/remove/{id}','OrderController@cart_remove');
      //model: Model/Order/model_cart_remove.php
      $data=model_cart_remove::removeData($id);
      return $data;

    }

    public function pilih_lab(){
      //name: karim
      //date create: 10 Feb 16
      //routes: Route::delete('order/cart/lab','OrderController@pilih_lab');
      //model: Model/Order/model_pilih_lab.php
      $data=model_pilih_lab::getData();
      return $data;

    }

    public function data_diri(Request $request){
      //name: karim
      //date create: 11 Feb 16
      //routes: Route::get('order/datadiri','OrderController@data_diri');
      //model: Model/Order/data_diri.php
      $data=model_data_diri::postData($request);
      return $data;

    }



    public function provinsi(){
      //name: karim
      //date create: 11 Feb 16
      //routes: Route::get('provinsi','OrderController@provinsi');
      //model: Model/Other/provinsi.php
      $data=model_provinsi::getData();
      return $data;
    }

    public function kabupaten($id){
      //name: karim
      //date create: 11 Feb 16
      //routes: Route::get('kabupaten','OrderController@kabupaten');
      //model: Model/Other/kabupaten.php
      $data=model_kabupaten::getData($id);
      return $data;
    }

    public function kecamatan($id){
      //name: karim
      //date create: 11 Feb 16
      //routes: Route::get('keluarahan','OrderController@keluarahan');
      //model: Model/Other/keluarahan.php
      $data=model_kecamatan::getData($id);
      return $data;
    }

    public function konfirmasi(Request $request){
      //name: karim
      //date create: 11 Feb 16
      //routes: Route::get('keluarahan','OrderController@keluarahan');
      //model: Model/Other/keluarahan.php
      $data=model_konfirmasi::getData($request);
      return $data;
    }

    public function order_input(){
      //name: karim
      //date create: 15 Feb 16
      //routes: Route::get('order/input','OrderController@order_input');
      //model: Model/Other/model_order_input.php
      $data=model_order_input::postData();
      return $data;
    }

    public function hasil_index(){
      //name: karim
      //date create: 51 Feb 16
      //routes: Route::get('hasil','OrderController@hasil_index');
      //model: Model/Other/hasil_index.php
      $data=model_hasil_index::getData();
      return $data;
    }




}
