<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



Route::get('/', function () {
    return view('welcome');
});


Route::get('karyawan','PatientController@index');

Route::get('karyawan/input','PatientController@input');

Route::post('karyawan/store','PatientController@store');

Route::get('karyawan/edit/{id}','PatientController@edit');

Route::post('karyawan/update/{id}','PatientController@update');

Route::get('karyawan/destroy/{id}','PatientController@destroy');





  Route::group(['prefix' => 'api/v1'], function () {

          //yang menggunakan cart letakan di group ini
          Route::group(['middleware' => ['web']], function () {
              //
              Route::post('order/cart/add','OrderController@cart_add');
              Route::get('order/cart/get','OrderController@cart_get');
              Route::get('order/cart/destroy','OrderController@cart_destroy');
              Route::delete('order/cart/remove/{id}','OrderController@cart_remove');
              Route::get('order/lab','OrderController@pilih_lab');
              Route::post('order/datadiri','OrderController@data_diri');
              Route::post('order/konfirmasi','OrderController@konfirmasi');
              Route::get('order/input','OrderController@order_input');
              Route::get('hasil','OrderController@hasil_index');

              Route::get('order/pemeriksaan/package','OrderController@pemeriksaan_package');
              Route::get('order/pemeriksaan/panel','OrderController@pemeriksaan_panel');

              Route::get('order/riwayat','OrderController@riwayat');
              Route::get('order/riwayat/detail/{id}','OrderController@riwayat_detail');
              Route::get('provinsi','OrderController@provinsi');
              Route::get('kabupaten/{id}','OrderController@kabupaten');
              Route::get('kecamatan/{id}','OrderController@kecamatan');

              Route::get('deskripsi/{id}','OtherController@deskripsi');


              Route::group(['middleware' => ['oauth']], function () {
                Route::get('check-token','PasswordGrantVerifier@check_token');
                Route::post('login','LoginController@login');
                Route::get('order/pemeriksaan/item','OrderController@pemeriksaan_item');
              });







              Route::post('oauth/access_token', function() {
               return Response::json(Authorizer::issueAccessToken());
                if(Authorizer::issueAccessToken()){
                 foreach (Authorizer::issueAccessToken() as $value) {
                   session(['token'=>$value]);
                 }
                }
              });


              Route::get('api', ['middleware' => ['oauth'], function() {
               $user_id=Authorizer::getResourceOwnerId(); // the token user_id
               $user=\App\User::find($user_id);// get the user data from database
               return Response::json($user);
              }]);



          });
  });






  Route::get('hash/{id}', function() {
    return Hash::make("{id}");
  });


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
