<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use LucaDegasperi\OAuth2Server\Authorizer;

class PasswordGrantVerifier extends Controller
{
    //
    public function verify($username, $password)
   {
       $credentials = [
         'email'    => $username,
         'password' => $password,
       ];

       if (Auth::once($credentials)) {
           return Auth::user()->id;
       }

       return false;
   }

   public function check_token(Authorizer $authorizer){
     return $authorizer->getAccessToken();
     if($authorizer->getAccessToken()==""){
       return "a";
     }
   }
}
