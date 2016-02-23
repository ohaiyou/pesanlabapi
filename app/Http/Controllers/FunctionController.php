<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FunctionController extends Controller
{


      //$currency=  app('App\Http\Controllers\FunctionController');
      //return $currency->getcurrency("500000");
      public function getcurrency($money){
          return 'Rp '.number_format($money, 0, ',', '.');
      }


}
