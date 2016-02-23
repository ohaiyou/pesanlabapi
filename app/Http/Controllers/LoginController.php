<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LucaDegasperi\OAuth2Server\Authorizer;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    //
    public function login(Authorizer $authorizer){
      $user_id=$authorizer->getAccessToken(); // the token user_id
      return $user_id;
    }
}
