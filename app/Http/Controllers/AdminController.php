<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Kerupuk;
use App\Models\Transaksi;
use App\Models\Vendor;
use App\Models\BarangV1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        $data = Transaksi::get();

        return view('admin.dashboar', compact('data'));
        //$data = Transaksi::get();
       // return response()->json($data);
    }

    public function history($order = 'desc')
    {
        $activity = Activity::orderBy('created_at', $order)->get();
        return view('admin.activity', compact('activity'));
    }

    public function kerupuk($order = 'asc')
    {
       // $kerupuk = Kerupuk::orderBy('nama_barang', $order)->get();
       $vendor = Vendor::get();

       $kerupuk = DB::table('master_barang')
        ->where('created_user_id',Auth::user()->id)
        ->orderBy('nama_barang', 'asc')
        ->get();
        return view('admin.master_barang', compact('kerupuk','vendor'));
    }

    public function vendor($order = 'asc')
    {
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
        
        // tambah validasi 'where nama_barang = ... and user_id = ... ' 

        // $request->validate([
        //     'nama_barang' => 'required|unique:master_barang,nama_barang',
        //     'harga_beli' => 'required|numeric',
        //     'harga_jual' => 'required|numeric',
        //     'stok' => 'required|integer',
        //     'gambar_barang' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        // ], [
        //     'nama_barang.required' => 'Nama barang wajib diisi.',
        //     'nama_barang.unique' => 'Nama barang sudah ada.',
        //     'harga_beli.required' => 'Harga beli wajib diisi.',
        //     'harga_beli.numeric' => 'Isilah harga beli dengan angka.',
        //     'harga_jual.required' => 'Harga jual wajib diisi.',
        //     'harga_jual.numeric' => 'Isilah harga jual dengan angka.',
        //     'stok.required' => 'Stok wajib diisi.',
        //     'stok.integer' => 'Masukkan angka stok yang benar.',
        //     'gambar_barang.image' => 'Masukkan gambar.',
        //     'gambar_barang.mimes' => 'Gambar harus berupa file bertipe: jpeg, png, jpg, gif.',
        //     'gambar_barang.max' => 'Gambar_barang tidak boleh lebih besar dari 2048 kb.',
        // ]);

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

        $vendor = Vendor::get();

        error_log('Auth user()->id : '.Auth::user()->id );


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

        $kerupuk = Kerupuk::find($request->id);

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
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'stok' => $request->stok,
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
        $kerupuk = Kerupuk::find($request->id);

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
