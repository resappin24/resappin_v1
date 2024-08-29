<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Kerupuk;
use App\Models\Transaksi;
use App\Models\Vendor;
use App\Models\BarangV1;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        $data = Transaksi::get();
      //   $user = User::where('username', 'Monicasan')->first();
        // auth('web')->login($user);
        if(Auth::check()){
          //  session()->regenerate();
          error_log("masuk dashbord auth");
            return view('admin.dashboar', compact('data'));
        }else {
            error_log("gamasuk dashbord, login");
            //redirect ke login lagi.
         //  return redirect('/')->with('error', 'Maaf, session sudah habis. Silahkan Login kembali.');
         Session::flash('error', 'Your session has ended. Please Login.');
          return redirect('/');
        //    return redirect('/')->withSuccess('Register Success! Please check your email to complete verification. Thankyou.');

        }

        error_log("dashboard luar");
      
        //$data = Transaksi::get();
       // return response()->json($data);
    }

    public function history($order = 'desc')
    {
     //   $activity = Activity::orderBy('created_at', $order)->get();
        
        $activity = DB::table('activity')
         ->select('activity.*')
        ->where('name_user',Auth::user()->name)
        ->orderBy('created_at', 'desc')
        ->get();

        return view('admin.activity', compact('activity'));
    }

    public function kerupuk($order = 'asc')
    {
       // $kerupuk = Kerupuk::orderBy('nama_barang', $order)->get();
       //function ambil list vendor sesuai dengan user login.
       $vendor = DB::table('master_vendor')->where('created_by',Auth::user()->id)->get();

      //kalau ambil semua data vendor tanpa filter ->  $vendor = Vendor::get();

    //    $kerupuk = DB::table('master_barang')
    //     ->where('created_user_id',Auth::user()->id)
    //     ->orderBy('nama_barang', 'asc')
    //     ->get();
    
        // ubah query tambah Nama Vendor.
        // $kerupuk = DB::table('master_barang_v1')
        //             ->join('master_vendor', 'master_vendor.vendor_id', '=', 'master_barang_v1.vendor_id')
        //             ->select('master_barang_v1.*', 'master_vendor.*')
        //             ->where('created_user_id',Auth::user()->id)
        //             ->orderBy('nama_barang', 'asc')
        //             ->get();
        
        //pakai leftjoin ke mastervendor.
        $kerupuk = DB::table('master_barang_v1')
                    ->leftJoin('master_vendor', 'master_vendor.vendor_id', '=', 'master_barang_v1.vendor_id')
                    ->select('master_barang_v1.*', 'master_vendor.*')
                    ->where('created_user_id',Auth::user()->id)
                    ->orderBy('nama_barang', 'asc')
                    ->get();

        return view('admin.master_barang', compact('kerupuk','vendor'));
    }

    public function vendor($order = 'asc')
    {
        //ambil data master_vendor sesuai dgn user login.
       $vendor = DB::table('master_vendor')->where('created_by',Auth::user()->id)->get();
        return view('admin.vendor', compact('vendor'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|unique:master_barang,nama_barang',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
            'gambar_barang' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama_barang.required' => 'Nama barang tidak boleh kosong',
            'nama_barang.unique' => 'Nama barang sudah ada.',
                'harga_beli.required' => 'Harga beli wajib diisi.',
                'harga_beli.numeric' => 'Isilah harga beli dengan angka.',
                'harga_jual.required' => 'Harga jual wajib diisi.',
                'harga_jual.numeric' => 'Isilah harga jual dengan angka.',
                'stok.required' => 'Stok wajib diisi.',
                'stok.integer' => 'Masukkan angka stok yang benar.',
                'gambar_barang.image' => 'Masukkan gambar.',
                'gambar_barang.mimes' => 'Gambar harus berupa file bertipe: jpeg, png, jpg, gif.',
                'gambar_barang.max' => 'Gambar_barang tidak boleh lebih besar dari 2048 kb.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
      
        // Validation passed
        $imgName = $request->hasFile('gambar_barang')
            ? 'img' . time() . '.' . $request->gambar_barang->extension()
            : 'gambar-default.png';

        if ($request->hasFile('gambar_barang')) {
            $request->gambar_barang->move(public_path('gambar_barang'), $imgName);
        }

        $kerupuk = Kerupuk::create([
            'nama_barang' => $request->nama_barang,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok,
            'gambar_barang' => $imgName,
            'created_at' => date('Y-m-d H:i:s'),
            'created_user_id' => Auth::user()->id,
            
        ]);
        error_log('Auth user()->id : '.Auth::user()->id );

        if ($kerupuk->wasRecentlyCreated) {
            Activity::create([
                'activity' => $request->activity,
                'name_user' => Auth::user()->name,
                'nama_barang' => $request->nama_barang,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->back()->with('success', 'Add kerupuk success.');
        } else {
            return redirect()->back()->withErrors(['errors' => 'Gagal menambahkan data.'])->withInput();
        }
    }

    public function addBarang(Request $request) {
  
          //validasi nama barang sudah ada jika user created nya sama. (sementara sambil nunggu table baru)
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required', //|unique:master_barang_v1,nama_barang (jika mau di modified multi user)
            'harga_beli' => 'required|numeric',
                'harga_jual' => 'required|numeric',
                'stok' => 'required|integer',
                'gambar_barang' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama_barang.required' => 'Nama barang tidak boleh kosong',
            'nama_barang.unique' => 'Nama barang sudah ada.',
                'harga_beli.required' => 'Harga beli wajib diisi.',
                'harga_beli.numeric' => 'Isilah harga beli dengan angka.',
                'harga_jual.required' => 'Harga jual wajib diisi.',
                'harga_jual.numeric' => 'Isilah harga jual dengan angka.',
                'stok.required' => 'Stok wajib diisi.',
                'stok.integer' => 'Masukkan angka stok yang benar.',
                'gambar_barang.image' => 'Masukkan gambar.',
                'gambar_barang.mimes' => 'Gambar harus berupa file bertipe: jpeg, png, jpg, gif.',
                'gambar_barang.max' => 'Gambar_barang tidak boleh lebih besar dari 2048 kb.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

      
        $imgName = $request->hasFile('gambar_barang')
        ? 'img' . time() . '.' . $request->gambar_barang->extension()
        : 'gambar-default.png';

    if ($request->hasFile('gambar_barang')) {
        $request->gambar_barang->move(public_path('gambar_barang'), $imgName);
    }

  //  error_log($request->vendorID);
  //validasi saat barang sudah mencapai limit (10) per akun user.
    $jmlItem = DB::table('master_barang_v1')
                ->leftJoin('master_vendor', 'master_vendor.vendor_id', '=', 'master_barang_v1.vendor_id')
                ->select('master_barang_v1.*', 'master_vendor.*')
                ->where('created_user_id',Auth::user()->id)->count();
    
    error_log('count item : '.$jmlItem);
    // validasi jika barang item per user sudah mencapai 10, tidak bisa lagi tambah.
    if($jmlItem >= 10) {

        return redirect()->back()->withErrors(['errors' => 'Sorry, you have reach the maximum number of adding master data.'])->withInput();

    } else {
        //if barang sudah ada per user created, error juga.

        $itemExists =  DB::table('master_barang_v1')
                    ->select('master_barang_v1.nama_barang')
                    ->where('nama_barang', $request->nama_barang)
                    ->where('created_user_id',Auth::user()->id)->count();
            
            if($itemExists > 0) {

                return redirect()->back()->withErrors(['errors' => 'Sorry, you cannot add duplicate items. '])->withInput();

            } else {


                    if ($request->vendorID == 'Pilih Vendor') {
                        $IDvendor = null;
                    }else {
                        $IDvendor = $request->vendorID;
                    }
                
                    $barang = BarangV1::create([
                        'vendor_id' => $IDvendor,
                        'nama_barang' => $request->nama_barang,
                        'main_harga_beli' => $request->harga_beli,
                        'main_harga_jual' => $request->harga_jual,
                        'main_stok' => $request->stok,
                        'new_harga_beli' => $request->harga_beli_new,
                        'gambar_barang' => $imgName,
                        'tanggal_beli' => $request->tgl_beli,
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_user_id' => Auth::user()->id,
                        
                    ]);
                
                    $vendorId = $request->vendorID;
                    error_log('vendor id : '.$vendorId );
                
                    if ($barang->wasRecentlyCreated) {
                        Activity::create([
                            'activity' => $request->activity,
                            'name_user' => Auth::user()->name,
                            'nama_barang' => $request->nama_barang,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    return redirect()->back()->with('success', 'Add new item success.');
                    
                        } else {
                            return redirect()->back()->withErrors(['errors' => 'Failed adding data.'])->withInput();
                        }
                    }

    }


    }

    public function addVendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_vendor' => 'required',
            'nama_vendor' => 'required'
        ], [
            'kode_vendor.required' => 'Kode vendor tidak boleh kosong',
            'nama_vendor.required' => 'Nama vendor tidak boleh kosong',
            
        ]);

        if ($validator->fails()) {
           return back()->withErrors($validator)->withInput();
        }

        if (Vendor::where('kode_vendor', $request->kode)->exists()) {
            
            $validator->errors()->add('kode_vendor', 'Kode vendor sudah terdaftar');
           // return back()->withErrors($validator)->withInput();
        }

        // tambah validasi 'and user_id = Auth->id 
        $validation = ['nama_vendor'=> $request->nama_vendor, 'created_by' => Auth::user()->id ];
         $vendor =   Vendor::where([
            ['nama_vendor','=',  $request->nama_vendor],
            ['created_by', '=', Auth::user()->id]
            ])->get()->first();

         if ($vendor !== null) {
            // jika ada, set vendor sudah terdaftar.
            return redirect()->back()->withErrors(['errors' => 'Vendor sudah terdaftar!']);
         } else {
            // process add vendor ke db
             $vendor = Vendor::create([
            'nama_vendor' => $request->nama_vendor,
            'kode_vendor' => $request->kode_vendor,
            'alamat' => $request->alamat,
            'no_telp' => '62'.$request->no_telp,
            'created_date' => now(),
            'created_by' => Auth::user()->id,
            
        ]);

        //insert ke log activity
        if ($vendor->wasRecentlyCreated) {
            Activity::create([
                'activity' => 'Add Master Vendor',
                'name_user' => Auth::user()->name,
                'nama_barang' => $request->nama_vendor,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->back()->with('success', 'Add vendor success.');
        } else {
            return redirect()->back()->withErrors(['errors' => 'Gagal menambahkan data.'])->withInput();
        }

        $request->session()->flash('success', 'Add New Master Vendor Success');
            return redirect()->back()->with('success', 'Add vendor success!');
         }

        return redirect('/vendor');

    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
            'gambar_barang' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_beli.numeric' => 'Isilah harga beli dengan angka.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Isilah harga jual dengan angka.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Masukkan angka stok yang benar.',
            'gambar_barang.image' => 'Masukkan gambar.',
            'gambar_barang.mimes' => 'Gambar harus berupa file bertipe: jpeg, png, jpg, gif.',
            'gambar_barang.max' => 'Gambar_barang tidak boleh lebih besar dari 2048 kb.',
        ]);

       // $kerupuk = Kerupuk::find($request->id);
       $kerupuk = BarangV1::find($request->id);

        if ($request->hasFile('gambar_barang')) {
            if ($request->hasFile('gambar_barang')) {

                $imgName = 'img' . time() . '.' . $request->gambar_barang->extension();
                $request->gambar_barang->move(public_path('gambar_barang'), $imgName);

                if ($kerupuk->gambar_barang && $kerupuk->gambar_barang != 'gambar-default.png') {
                    $oldImagePath = public_path('gambar_barang/') . $kerupuk->gambar_barang;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            } else {
                $gambar = $kerupuk->gambar_barang;
            }
            $kerupuk->update([
                'nama_barang' => $request->nama_barang,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'stok' => $request->stok,
                'gambar_barang' => $imgName,
                'activity' => $request->activity,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            Activity::insert([
                'activity' => $request->activity,
                'name_user' => Auth::user()->name,
                'nama_barang' => $request->nama_barang,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->back()->with('success', 'Update kerupuk berhasil.');
        } else {
            $kerupuk->update([
                'nama_barang' => $request->nama_barang,
                'main_harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'main_stok' => $request->stok,
                'activity' => $request->activity,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            Activity::insert([
                'activity' => $request->activity,
                'name_user' => Auth::user()->name,
                'nama_barang' => $request->nama_barang,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->back()->with('success', 'Update kerupuk berhasil.');
        }
    }


    public function destroy(Request $request)
    {
        $kerupuk = BarangV1::find($request->id);

        if (!$kerupuk) {
            return redirect()->back()->with('error', 'Kerupuk tidak ditemukan.');
        }

        $imagePath = public_path('gambar_barang/' . $kerupuk->gambar_barang);

        if ($kerupuk->stok == 0) {
            if ($kerupuk->gambar_barang == 'gambar-default.png') {
                $kerupuk->delete();
            } else if (file_exists($imagePath)) {
                unlink($imagePath);
                $kerupuk->delete();
            }
            Activity::insert([
                'activity' => 'Delete Master Barang',
                'name_user' => Auth::user()->name,
                'nama_barang' => $kerupuk->nama_barang,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return redirect()->back()->with('success', 'Delete kerupuk berhasil.');

        } else {
            return redirect()->back()->withErrors(['errors' => 'Stok kerupuk masih tersedia.'])->withInput();
        }
    }

    public function deleteVendor(Request $request)
    {
        $vendor = Vendor::find($request->id);

        if (!$vendor) {
            return redirect()->back()->with('error', 'Vendor tidak ditemukan.');
        } else {
            $vendor->delete();
            
            // insert list activity
            Activity::insert([
                'activity' => 'Delete Master Vendor',
                'name_user' => Auth::user()->name,
                'nama_barang' => $vendor->nama_vendor,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->back()->with('success', 'Delete Vendor berhasil.');
        }

      
    }

}
