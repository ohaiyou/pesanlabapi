<?php

namespace App\Models\Order;
use Cart;
use Illuminate\Database\Eloquent\Model;

class model_cart_get extends Model
{
    //
    public static function getData(){
  		    //
      return  Cart::instance('cart')->content();
    }
}
