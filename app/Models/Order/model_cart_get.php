<?php

namespace App\Models\Order;
use Cart;
use Illuminate\Database\Eloquent\Model;

class model_cart_get extends Model
{
    //
    public static function getData(){
  		    //
          $idx=0;
          $arr=array();

          foreach ( Cart::instance('cart')->content() as $key => $value) {
            # code...

            $arr[$idx]["id"]=$value->id;
            $arr[$idx]["name"]=$value->name;
            $arr[$idx]["preparation"]=$value->options->preparation;
            $idx+=1;
          }


      //return  Cart::instance('cart')->content();
      return Response()->json(array(
                  'error'     =>  false,
                  'stores'    =>  $arr),
                  200
          );

    }
}
