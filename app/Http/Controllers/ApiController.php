<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class ApiController extends Controller
{

  public function register(Request $req){
    $validator = \Validator::make($req->all(), [
        'nama' => 'required',
        'email' => 'required|unique:users',
        'password' => 'required',
        'tanggal' => 'required',
        'jeniskelamin' => 'required',
        'no_telp' => 'required',
    ]);


    if ($validator->fails()) {
      return  $validator->messages()->toJson();

    }

    $users = new \App\User();
    $users->nama = $req->input('nama');
    $users->email = $req->input('email');
    $users->password = bcrypt($req->input('password'));
    $users->tanggal = $req->input('tanggal');
    $users->jeniskelamin = $req->input('jeniskelamin');
    $users->no_telp = $req->input('no_telp');
    $users->save();

    return response()->json(
      [
        'success' => 1,
        'status' => 200,
        'message' => 'Register Success'
      ]
    );
  }

  public function login(Request $req){
    $validator = \Validator::make($req->all(), [
        'email' => 'required',

    ]);


    if ($validator->fails()) {
      return  $validator->messages()->toJson();
    }

    else {
      $users = \DB::table('users')->where('email',$req->email)->get();
      if (\Hash::check($req->password,$users[0]->password)) {
        $req->session()->put('user_login',$users[0]->id);
        return response()->json(
          [
            'id_login' => $req->session()->get('user_login'),
            'success' => 1,
            'status' => 200,
            'message' => 'Login Success'
          ]
        );
      }
      else{
        return response()->json(
          [
            'success' => 0,
            'status' => 200,
            'message' => 'Wrong Email and/or Password'
          ]
        );
      }

    }
  }
  public function profile(Request $req)
  {
    $user = User::findOrFail($req->id);

    if (count($user)==0) {
      return response()->json([
        'success' => 0,
        'status' => 200,
        'message'  =>  'Anda harus login terlebih dahulu'
      ]);
    }

    return response()->json([
      'success' => 1,
      'status' => 200,
      'user'  =>  $user
    ]);
  }

  public function logout(Request $req)
  {
    return response()->json([
      'success' =>  1,
      'status'  =>  200,
      'message' =>  'Anda berhasil keluar'
    ]);
  }

  public function simpanlokasi(Request $req)
  {
    $lokasi = new \App\Lokasi;
    $lokasi->id_user = $req->idUser;
    $lokasi->lat = $req->lat;
    $lokasi->lon = $req->lon;
    $lokasi->save();
  }

  public function undangankeluarga(Request $req)
  {
    $user = \App\User::where('email',$req->email)->get();
    $anggota_keluargas = \App\anggota_keluarga::where('id_anggota', $user[0]->id)->get();
    if(count($anggota_keluargas) > 0){
      //invite

      $newanggota = new \App\anggota_keluarga;
      $newanggota->id_keluarga = $anggota_keluargas[0]->id_keluarga;
      $newanggota->id_anggota = $user[0]->id;
      $newanggota->terima = false;
      $newanggota->type = "Anggota";
      $newanggota->save();
    }
    else {
      $newkeluarga = new \App\Keluarga;
      $newkeluarga->nama_keluarga = 'Keluarga';
      $newkeluarga->save();

      $newanggota = new \App\anggota_keluarga;
      $newanggota->id_keluarga = $newkeluarga->id;
      $newanggota->id_anggota = $req->idinvite;
      $newanggota->terima = true;
      $newanggota->type = "Anggota";
      $newanggota->save();

      $newanggota = new \App\anggota_keluarga;
      $newanggota->id_keluarga = $newkeluarga->id;
      $newanggota->id_anggota = $user[0]->id;
      $newanggota->terima = false;
      $newanggota->type = "Anggota";
      $newanggota->save();
    }
  }
  public function ambilLokasi(Request $req){
    return \App\Lokasi::groupBy('id_user')->get();
  }
}
