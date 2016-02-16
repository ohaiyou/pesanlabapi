<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\KaryawanGet;
use App\KaryawanStore;
use App\KaryawanEdit;
use App\KaryawanUpdate;
use App\KaryawanDelete;
class PatientController extends Controller
{
    //
    public function index(){
      $data=KaryawanGet::getData();
      return View('index')->with('data',$data);
    }
    public function input(){
      return View('tes');
    }
    public function store(Request $request){
      KaryawanStore::store($request);
        return Redirect('karyawan');
    }

    public function edit($id){
      $data=KaryawanEdit::getData($id);
      return View('edit')->with("data",$data);
    }

    public function update(Request $request){
      $data=KaryawanUpdate::updateData($request);
    return Redirect('karyawan');
    }
    public function destroy($id){
      $data=KaryawanDelete::destroy($id);
      //return $data;
      return Redirect('karyawan');
    }
}
