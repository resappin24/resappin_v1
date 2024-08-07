<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Transaksi;
use Laravel\Socialite\Two\GoogleProvider;

class GoogleOauthController extends Controller
{
    //
    public function redirect()
    {
      return Socialite::driver('google_signin')->redirect();
    }

    public function signInRedirect()
    {
        $driver = Socialite::buildProvider(
            GoogleProvider::class, config('services.google_signin')
        );

       return $driver->redirect();
    }

    public function callback_login(Request $request)
    {
        // Google user object dari google
        $driver = Socialite::buildProvider(
          GoogleProvider::class, config('services.google_signin')
         );

       // $userFromGoogle = Socialite::driver('services.google_signin')->stateless()->user();
       $userFromGoogle = $driver->stateless()->user();
       
        // Ambil user dari database berdasarkan google user id
        $userFromDatabase = User::where('google_id', $userFromGoogle->getId())->first();
     
        // tambah validasi jika register dgn google tapi tdk pakai google oauth.
        // Jika tidak ada user, maka buat user baru
        if (!$userFromDatabase) {

          // validasi lagi jika email google nya sudah dipakai.
          $userEmail = User::where('email', $userFromGoogle->getEmail())->first();
          if (!$userEmail) {
            // blom ada google_id & email. arahin ke sign up.
            return redirect('/')->with('error', 'Maaf, Alamat email belum terdaftar. Silahkan Register / Sign Up terlebih dahulu pada Register Page. Terimakasih.');
        
        } else {
          // google id ga ada, email sudah dipakai menggunakan login biasa.
          return redirect('/')->with('error', 'Maaf, Alamat email sudah terdaftar menggunakan password. Silahkan login menggunakan password atau Forgot password.');
        }
        }  else {
          // sudah terdaftar id googlenya, langsung direct ke dashboard,

            $user = User::where('username', $userFromGoogle->getName())->first();

          //  Auth::user($newUser);
            Auth::loginUsingId($user->id);
            $data = Transaksi::get();

            // update last_login.

             return redirect('/dashboard');

        }
        
    }

    public function callback_register(Request $request)
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
            $newUser = new User([
                'google_id' => $userFromGoogle->getId(),
                'name' => $userFromGoogle->getName(),
                'email' => $userFromGoogle->getEmail(),
                'password' => '',
                'username' => $userFromGoogle->getName(),
            ]);

            $newUser->save();

            Auth::user($newUser);

            $infologin = [
                'username' => $userFromGoogle->getName(),
                 'password' => '',
            ];

            error_log("newUser = ". $newUser);
           // dd(Auth::user());
            error_log("auth = ". Auth::user());
            
            $user = User::where('username', $userFromGoogle->getName())->first();
          //  error_log("user : ", $user.toString());
          if($user){
           // cek email sukses terdaftar..  
           return redirect('/')->withSuccess('Registration Success! Please Login and Verification your email. Thankyou.');
            
          }else {
            return redirect('/')->with('error', 'Sorry, your registration unsuccessful, please register again.');
          }
        
        } else {
          // error sudah terdaftar email, tapi google_id tidak ada. return email sudah dipakai menggunakan login biasa.
          return redirect('/')->with('error', 'Maaf, Alamat email sudah terdaftar menggunakan password. Silahkan login menggunakan password atau Forgot password.');
        }
      }
        
         else {
            // google id sudah terdaftar juga. arahin sudah terdaftar.
            return redirect('/')->with('error', 'Maaf, Alamat email sudah terdaftar menggunakan Google Sign Up. Silahkan Login menggunakan Google Sign In.');

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
