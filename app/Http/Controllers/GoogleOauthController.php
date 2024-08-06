<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Transaksi;


class GoogleOauthController extends Controller
{
    //
    public function redirect()
    {
      return Socialite::driver('google')->redirect();
    }

    public function callback_login(Request $request)
    {
        // Google user object dari google
        $userFromGoogle = Socialite::driver('google')->stateless()->user();

        // Ambil user dari database berdasarkan google user id
        $userFromDatabase = User::where('google_id', $userFromGoogle->getId())->first();
     
        // tambah validasi jika register dgn google tapi tdk pakai google oauth.
        // Jika tidak ada user, maka buat user baru
        if (!$userFromDatabase) {

          // validasi lagi jika email google nya sudah dipakai.
          $userEmail = User::where('email', $userFromGoogle->getEmail())->first();
          if (!$userEmail) {
            return redirect('/')->with('error', 'Maaf, Alamat email belum terdaftar. Silahkan Register / Sign Up terlebih dahulu pada Register Page. Terimakasih.');
        
        } else {
          // return email sudah dipakai menggunakan login biasa.
          return redirect('/')->with('error', 'Maaf, Alamat email sudah terdaftar menggunakan password. Silahkan login menggunakan password atau Forget password');
        }
      }
        
         else {
          // sudah terdaftar id googlenya, langsung direct ke dashboard,

          $newUser = new User([
            'google_id' => $userFromGoogle->getId(),
            'name' => $userFromGoogle->getName(),
            'email' => $userFromGoogle->getEmail(),
            'password' => '',
            'username' => $userFromGoogle->getName(),
            ]);

            $user = User::where('username', $userFromGoogle->getName())->first();

          //  Auth::user($newUser);
            Auth::loginUsingId($user->id);
            $data = Transaksi::get();

             return redirect('/dashboard');

        }
        
    }

    public function logout(Request $request)
    {
        auth('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
