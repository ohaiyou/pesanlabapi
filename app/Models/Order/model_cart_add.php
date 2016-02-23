<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use Cart;
class model_cart_add extends Model
{
    //
    public static function getData($request){
  		    //
  		  Cart::instance('cart')->add(array(
    		  array(
    		  	'id' => $request->code,
    		  	'name' => $request->nama,
    		  	'qty' => 1,
    		  	'price' => 0,
            'options'=>array(
              'preparation'=>$request->preparation
            )
    		  )
  		  ));


          return Cart::instance('cart')->content();
    }
}
