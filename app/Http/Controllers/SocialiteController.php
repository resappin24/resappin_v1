<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;


class SocialiteController extends Controller
{
    //
    public function redirect()
    {
      return Socialite::driver('google')->redirect();
    }


    public function callback()
    {
        // Google user object dari google
        $userFromGoogle = Socialite::driver('google')->stateless()->user();

        // Ambil user dari database berdasarkan google user id
        $userFromDatabase = User::where('google_id', $userFromGoogle->getId())->first();

        // Jika tidak ada user, maka buat user baru
        if (!$userFromDatabase) {
            $newUser = new User([
                'google_id' => $userFromGoogle->getId(),
                'name' => $userFromGoogle->getName(),
                'email' => $userFromGoogle->getEmail(),
                'password' => '',
                'username' => $userFromGoogle->getName(),
            ]);

            $newUser->save();

            Auth::login($newUser);

            $infologin = [
                'username' => $userFromGoogle->getName(),
                 'password' => '',
            ];

            // Login user yang baru dibuat
            // auth('web')->login($newUser);
            // session()->regenerate();

            // return redirect('/dashboard');
            // return redirect('/');
            // if (Auth::attempt($infologin)){
            //     if (Auth::user()) {
            //         // validasi Otp / link.
            //         // $emailVerified = User::where('username', $request->username)->first();

            //         // if ($emailVerified->email_verified_at == null) {
            //         // }
            //         Auth::user()->name;
            //         return redirect('/dashboard');

            //     }
            // } 
        }else {
            return redirect('/')->withErrors('Username atau password yang anda masukkan salah');
        }


        // Jika ada user langsung login saja
      //  auth('web')->login($userFromDatabase);
     //   session()->regenerate();
        if(Auth::check()){
            $user =  User::where('username', $userFromGoogle->getName())->get();
           //  $username = $user->username;
             return redirect()->intended('/dashboard')->with('user', $user);
        } else {
            return redirect('/nnn');
        }
        
        // $user =  User::where('username', $userFromGoogle->getName())->first();
        // $username = $user->username;
        // return redirect()->route('/dashboard')->with( ['user' => $user] );
        //return redirect()->intended('/dashboard')->with('user', $username);
    }

    public function logout(Request $request)
    {
        auth('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
