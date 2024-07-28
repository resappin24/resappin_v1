<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;


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

            Auth::user($newUser);

            $infologin = [
                'username' => $userFromGoogle->getName(),
                 'password' => '',
            ];

            // Login user yang baru dibuat
            auth('web')->login($newUser);
            // session()->regenerate();

            error_log("newUser = ". $newUser);
           // dd(Auth::user());
            error_log("auth = ". Auth::user());
            
            $user = User::where('username', $userFromGoogle->getName())->first();
          //  error_log("user : ", $user.toString());
          if($user){
            //creturn redirect()->route('dashboard', ['user' => $user])->with('message', 'State saved correctly!!!');
            $data = Transaksi::get();

           // return view('admin.dashboar', compact('data','user'));
            return redirect('/dashboard');
          }else {
            return redirect('/mm');
          }
           //return redirect()->intended('/dashboard')->with('user', $user);
            // return redirect('/dashboard');
            //     }
            // } 
        }else {
            $user = User::where('username', $userFromGoogle->getName())->first();
            //  error_log("user : ", $user.toString());
         
            $infologin2 = [
                'username' => $user->username,
                 'email' => $user->email,
            ];

            error_log("user: " . $user);
            error_log("user-username = ".$user->username);

         //   if (Auth::attempt($infologin2)) {
                session()->put('user', [
                    'email' => $userFromGoogle->getEmail(),
                    'username' => $userFromGoogle->getName(),
                ]);

             // error_log("auth login = ". Auth::login());

             $data = Transaksi::get();
             return view('admin.dashboar', compact('data','user'));
            //   return redirect('/dashboard');
            // }else {
            //   return redirect('/mm');
            // }
          //  return redirect('/pp')->withErrors('Username atau password yang anda masukkan salah');
        }


        // Jika ada user langsung login saja
      //  auth('web')->login($userFromDatabase);
     //   session()->regenerate();
        if(Auth::check()){
           // dump(Auth::user());
            $user =  User::where('username', $userFromGoogle->getName())->get();
           //  $username = $user->username;
           $newUser2 = new User([
                'google_id' => $userFromGoogle->getId(),
                'name' => $userFromGoogle->getName(),
             ]);
             error_log("newUser2 = ". $newUser2);
             
             auth('web')->login($newUser2);
             Auth::login($user);
             session()->regenerate();
             error_log("auth = ". Auth::user());
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
