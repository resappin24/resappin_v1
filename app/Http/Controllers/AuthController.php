<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function verifyEmail($email)
    {
        // cek emailnya, update email_verified_at = now(). lanjut ke login.
        $cekEmailRegistered = DB::table('users')->where('email',$email)->limit(1);
        $cekEmailRegistered2 = User::where('email', $email)->first();

       
        if($cekEmailRegistered){
            //update email_verified_at = now.
           $cekEmailRegistered->update(['email_verified_at' => now()]);
       
           $user = User::where('email', $email)
           ->first();

           if($user){
            if (Auth::loginUsingId($user->id)) {

                return redirect('verify-success/'. $email)->withSuccess('Your email has been verified!');
            }else {
                return redirect('/failed-verification');
            }

           }else {
            return redirect('/failed-verification');
        }
           
        }else {
      
           return redirect('/failed-verification');
        }
   
}

    public function verifySuccess($email) {
     //   if (Auth::attempt(['email' => $email, 'password' => $password])) {
        //cek lagi emailnya, jika verification_at tidak null, direct ke dashboard.

        $checkUser = DB::table('users')->where('email',$email)->first();

        //error_log("checkUser : ". $checkUser.toString());
    
        header("Refresh:5; url=http://127.0.0.1:8000/dashboard");
          return view ('session.verify');
         
       
    }

    // Metode untuk mengirim ulang email verifikasi
    public function resendEmailVerification(Request $request)
    {

        //cek email jika sudah terdaftar.
        $checkEmail = User::find($request->email);
        
        if($checkEmail) {
            // jika ada, kirim email lagi.
            $newUserId = $checkEmail->id;
            $newUser = User::find($newUserId);
            Mail::to($newUser->email)
            ->send(new EmailVerification($newUser));

            return redirect('/')->withSuccess('Resend Email Verification Link Success! Please check your email to complete verification. Thankyou.');


        }else {
            //return back with errors.

        }

    }

    public function failedVerification() {

        session()->put('failed', 'failed');

        return view('session.verify-account');
    }

    public function pendingVerification() {
        //jika email_verified_at masih null, masuk kesini.
      //  dd($email);
        
        session()->forget('failed');
        session()->flush();
        session()->put('pending', 'pending.');

        return view('session.verify-account');
    }

    public function index()
    {
        return view('session.login2');
    }

    public function register()
    {
        return view('session.register2');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|min:3|max:255',
            'password' => 'required|max:255'
        ], [
            'username.required' => 'Username tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong'
        ]);

        $infologin = [
            'username' => $request->username,
            'password' => $request->password,
        ];
        
        if (Auth::attempt($infologin)){
            if (Auth::user()) {
                // cek email verified at..
                $emailVerified = User::where('username', $request->username)->first();

                if ($emailVerified->email_verified_at == null) {
                    //jika masih null, arahkan ke page pendingverified, dan kirim email link verifikasi langsung.
                    $newUserId = $emailVerified->id;
                    $newUser = User::find($newUserId);
                    Mail::to($newUser->email)
                    ->send(new EmailVerification($newUser));

                    return redirect('pending-verification');
                    // return redirect()->route('pending', ['email'=>$request->email]);

                } else {
                    //jika sudah verified tidak null, mka direct ke dashboard.
                    return redirect('/dashboard');
                }
              
            }
        } else {
            return redirect('/')->withErrors('Username atau password yang anda masukkan salah');
        }

    }

    public function loginJson(Request $request)
    {
        $request->validate([
            'username' => 'required|min:3|max:255',
            'password' => 'required|max:255'
        ], [
            'username.required' => 'Username tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong'
        ]);

        $infologin = [
            'username' => $request->username,
            'password' => $request->password,
        ];
        
        if (Auth::attempt($infologin)){
            if (Auth::user()) {
                // cek email verified at..
                $emailVerified = User::where('username', $request->username)->first();

                if ($emailVerified->email_verified_at == null) {
                    //jika masih null, arahkan ke page pendingverified, dan kirim email link verifikasi langsung.
                    $newUserId = $emailVerified->id;
                    $newUser = User::find($newUserId);
                    Mail::to($newUser->email)
                    ->send(new EmailVerification($newUser));

                // yg asli :    return redirect('pending-verification');
                    // return redirect()->route('pending', ['email'=>$request->email]);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Email has not been verified',
                    ], 401);

                } else {
                    //jika sudah verified tidak null, mka direct ke dashboard.
                    //return redirect('/dashboard');
                    return response()->json([
                        'status' => 'success',
                        'user' => $emailVerified->username,
                    ], 200);
                }
              
            }
        } else {
          //  return redirect('/')->withErrors('Username atau password yang anda masukkan salah');
          return response()->json([
            'status' => 'error',
            'message' => 'Username atau password yang anda masukkan salah',
        ], 402);
        }

    }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|min:3|max:255',
            'email' => 'required|unique:users|email',
            'password' => 'required|max:255',
        ], [
            'name.required' => 'Name tidak boleh kosong',
            'username.required' => 'Username tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Alamat Email sudah digunakan',
            'password.required' => 'Password tidak boleh kosong',
        ]);

        //generate token
        $token = strtoupper(bin2hex(random_bytes(5)));

        // Create a new instance of the User model
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'created_at' => date('Y-m-d H:i:s'),
            'verification_token' => $token
        ]);

        if ($user) {
            $newUserId = $user->id;
            $newUser = User::find($newUserId);
            Mail::to($newUser->email)
            ->send(new EmailVerification($newUser));
            //->bcc('resappin.tech@gmail.com')
            

            error_log("newUser = ". $newUser);

            // session()->put('otpRoute', 'register');

            // $request->session()->flash('success', 'Registrasi Berhasil!');
            return redirect('/')->withSuccess('Register Success! Please check your email to complete verification. Thankyou.');

           // return redirect('/');
          //  return redirect('/')->with('success', 'Registrasi berhasil. Silakan periksa email Anda untuk verifikasi.');
        } else {
            return redirect('/')->with('error', 'Registrasi gagal. Silakan coba lagi.');
        }
    }
    

    function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
