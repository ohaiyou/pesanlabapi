<?php

namespace App\Models\Order;
use Cart;
use Illuminate\Database\Eloquent\Model;

class model_cart_remove extends Model
{
    //
    public static function removeData($id){
      try {
				$rw= Cart::instance('cart')->search(array('id' =>$id));
				$str = serialize($rw);
				foreach($rw as $value){
				 Cart::instance('cart')->remove($value);
				}
				return "deleted ".$id;
			} catch (Exception $e) {
					//return Redirect::to('order/pemeriksaan/panel/1');
			}
    }
}
