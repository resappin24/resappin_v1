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
        // $user = User::findOrFail($id);

        // if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        //     return redirect()->back()->with('error', 'Verifikasi email gagal. Invalid verification link.');
        // }

        // if ($user->hasVerifiedEmail()) {
        //     return redirect('/')->with('info', 'Email sudah diverifikasi sebelumnya.'); // Ganti dengan halaman yang sesuai
        // }

        // if ($user->markEmailAsVerified()) {
        //     event(new Verified($user));
        //     return redirect('/')->with('success', 'Email berhasil diverifikasi.'); // Ganti dengan halaman yang sesuai
        // }

        // return redirect()->back()->with('error', 'Verifikasi email gagal. Silakan coba lagi.');
        // ========================

        // cek emailnya, update email_verified_at = now(). lanjut ke login.
        $cekEmailRegistered = DB::table('users')->where('email',$email)->limit(1);

        error_log("email = ". $email);
       // error_log("cekEmailRegis = ". $cekEmailRegistered);

        if($cekEmailRegistered){
            //update email_verified_at = now.
           $cekEmailRegistered->update(['email_verified_at' => now()]);
            // return response()->json(['message' => 'verify'], 200);

            // return view('session.verify');
            return redirect('/verify-success')->withSuccess('Your email has been verified! Please go back to Login page.');

        }else {
           //return view ('failed.verified');
                 
        }

       // return view('session.verify');
       
    }

    public function verifySuccess() {
        return view ('session.verify');
    }

    // Metode untuk mengirim ulang email verifikasi
    public function resendEmailVerification(Request $request)
    {
        // if ($request->user()->hasVerifiedEmail()) {
        //     return redirect('/'); // Ganti dengan halaman yang sesuai
        // }

        // $request->user()->sendEmailVerificationNotification();

        // return back()->with('resent', true);

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

    public function index()
    {
        return view('session.login');
    }

    public function register()
    {
        return view('session.register');
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
                return redirect('/dashboard');
            }
        } else {
            return redirect('/')->withErrors('Username atau password yang anda masukkan salah');
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
