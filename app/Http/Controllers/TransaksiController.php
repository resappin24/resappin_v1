<?php

namespace App\Http\Controllers;

use App\Models\Kerupuk;
use App\Models\Transaksi;
use App\Models\Kategori;
use App\Models\BarangV1;
use App\Models\Repack;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class TransaksiController extends Controller
{
    public function transaksi(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ], [
            'date.nullable' => 'Tentukan tanggal terlebih dahulu',
            'start_date.nullable' => 'Tentukan tanggal terlebih dahulu',
            'end_date.nullable' => 'Tentukan tanggal terlebih dahulu',
        ]);
        
       // $kerupuk = Kerupuk::get();

       // $kerupuk = BarangV1::get();

        $kerupuk = DB::table('master_barang_v1')->where('created_user_id',Auth::user()->id)->get();

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : null;
        $start = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null;
        $end = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null;

        error_log('end date'. $end);
        // $transaksi = Transaksi::select('*')
        // ->when($selectedDate, function ($query) use ($selectedDate) {
        //     $query->whereDate('created_at', $selectedDate)
        //     ->where('created_by',Auth::user()->id);
        // })
        // ->when($start && $end, function ($query) use ($start, $end) {
        //     $query->where('created_at', '>=', $start)
        //         ->where('created_at', '<', Carbon::parse($end)->addDay())
        //         ->where('created_by',Auth::user()->id)
        //         ->orderBy('created_at', 'asc');
        // })
        // ->when(!$selectedDate && !$start && !$end, function ($query) {
        //     $query->whereDate('created_at', Carbon::now()->toDateString())
        //             ->where('created_by',Auth::user()->id);
        // })
        // ->get();

        $transaksi = DB::table('transaksi')
        ->select('transaksi.id_barang','transaksi.nama_barang', 'transaksi.qty', 'transaksi.modal', 'transaksi.satuan','transaksi.subtotal','transaksi.created_at as created_at', 'transaksi.updated_at', 'transaksi.transaksiID','transaksi.id_barang')
        ->when($selectedDate, function ($query) use ($selectedDate) {
            $query->whereDate('created_at', $selectedDate)
            ->where('created_by',Auth::user()->id);
        })
        ->when($start && $end, function ($query) use ($start, $end) {
            $query->where('created_at', '>=', $start)
                ->where('created_at', '<', Carbon::parse($end)->addDay())
                ->where('created_by',Auth::user()->id)
                ->orderBy('created_at', 'DESC');
        })
        ->when(!$selectedDate && !$start && !$end, function ($query) {
            $query->whereDate('created_at', Carbon::now()->toDateString())
                    ->where('created_by',Auth::user()->id);
        })
        ->get();
            
        // if (!$request->has('date') && (!$request->has('start_date') || !$request->has('end_date'))) {
        //     return redirect()->back()->withErrors(['errors' => 'Tanggal belum ditentukan.'])->withInput();
        // }
        return view('admin.transaksi', compact('transaksi', 'kerupuk', 'selectedDate', 'start', 'end'));
    }

    public function date(Request $request)
    {
        $currentDate = Carbon::now()->toDateString();

        $kerupuk = BarangV1::get();

        $selectedDate = $request->input('date');
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $transaksi = Transaksi::select('*')
        ->where(function ($query) use ($start, $end, $selectedDate, $currentDate) {
            $query->whereDate('transaksi.created_at', $start)
                ->orWhereDate('transaksi.created_at', $end)
                ->orWhereDate('transaksi.created_at', $currentDate)
                ->orWhereDate('transaksi.created_at', $selectedDate);
        })
        ->get();
        
        if (!$request->has('date') && (!$request->has('start_date') || !$request->has('end_date'))) {
            return redirect()->back()->withErrors(['errors' => 'Tanggal belum ditentukan.'])->withInput();
        }
    }

    public function store_transaksi(Request $request)
    {
        $request->validate([
            'qty' => ['required', 'numeric', 'gt:0'],
            'nama_barang' => 'required',
        ], [
            'nama_barang.required' => 'Pilih barang terlebih dahulu',
            'qty.required' => 'Qty tidak boleh 0',
            'qty.numeric' => 'Qty harus berupa angka',
            'qty.gt' => 'Qty tidak boleh 0',
        ]);

        $kerupuk = BarangV1::find($request->kerupukID);

        error_log("kerupuk : ". $kerupuk);
        error_log("id_barang : ". $request->id_barang);
        
        error_log("trans date : ".$request->input('backdate'));

        if ($kerupuk && $kerupuk->main_stok >= $request->qty) {
            Transaksi::insert([
                'id_barang' => $request->kerupukID,
                'nama_barang' => $request->nama_barang,
                'qty' => $request->qty,
                'modal' => $request->modal,
                'satuan' => $request->satuan,
                'subtotal' => $request->subtotal,
                'transaction_date' => $request->input('backdate'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id,
            ]);

            $kerupuk->main_stok -= $request->qty;
            $kerupuk->save();

            //blom tambah ke activity
             //insert ke log activity
       
            Activity::create([
                'activity' => 'Add New Transaction',
                'name_user' => Auth::user()->name,
                'nama_barang' => $request->nama_barang,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            return redirect()->back()->with('success', 'Data transaksi berhasil ditambahkan.');

        } else {
            return redirect()->back()->withErrors(['errors' => 'Data belum lengkap.'])->withInput();
        }
    }


    public function update_transaksi(Request $request)
    {
        $request->validate([
            'qty' => ['required', 'numeric', 'gt:0'],
        ], [
            'qty.required' => 'Qty tidak boleh 0',
            'qty.numeric' => 'Qty harus berupa angka',
            'qty.gt' => 'Qty tidak boleh 0',
        ]);

        $transaksi = Transaksi::find($request->id);

        $oldQty = $transaksi->qty;

        $transaksi->update([
            'kerupukID' => $request->kerupukID,
            'nama_barang' => $request->nama_barang,
            'qty' => $request->qty,
            'modal' => $request->modal,
            'satuan' => $request->satuan,
            'subtotal' => $request->subtotal,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $kerupuk = Kerupuk::find($request->kerupukID);

        if ($kerupuk) {
            $newStok = $request->qty - $oldQty;
            $kerupuk->stok -= $newStok;
            $kerupuk->save();

            echo $newStok;
        }

        return redirect()->back()->with('success', 'Data transaksi berhasil diperbarui.');
    }
    public function get_transaksi()
    {
        $data = Transaksi::get();

        $transaksi = DB::table('transaksi')
        ->select('transaksi.*')
        ->where('created_by',Auth::user()->id)
        ->where('created_at','>=', now()->addDays(-7)->toDateTimeString())
        ->get();

        return response()->json($transaksi);
    }

    public function showDetails()
{
                       $data = Transaksi::get();

                    $salesDetails = DB::table('transaksi')
        ->select('transaksi.*')
        ->where('created_by',Auth::user()->id)
        ->where('created_at','>=', now()->addDays(-7)->toDateTimeString())
        ->orderBy('created_at', 'DESC')
        ->get();

    if ($salesDetails->isEmpty()) {
        return response()->json(['error' => 'No data found for the last 7 days'], 404);
    }

    // Kirim data ke view partial untuk menampilkan di modal
    return view('sales.details', compact('salesDetails'))->render();
}

    public function get_kategori() {
        $data = Kategori::get();
        return response()->json($data);
    }

    public function kategori($order = 'asc')
    {
       $kategori = DB::table('kategori_barang')->where('created_by',Auth::user()->id)->get();
        return view('admin.kategori', compact('kategori'));
    }

    public function addKategori(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'kategori' => 'required'
        ], [
            'kategori.required' => 'Kategori tidak boleh kosong'
            
        ]);

        if ($validator->fails()) {
           return back()->withErrors($validator)->withInput();
        }

        if (Kategori::where('kategori', $request->kategori)->exists()) {
            
            $validator->errors()->add('kategori', 'Kategori sudah terdaftar!');
           // return back()->withErrors($validator)->withInput();
        }

        // tambah validasi 'and user_id = Auth->id 
        $validation = ['kategori'=> $request->kategori, 'created_by' => Auth::user()->id ];
         $kategori =   Kategori::where([
            ['kategori','=',  $request->kategori],
            ['created_by', '=', Auth::user()->id]
            ])->get()->first();

         if ($kategori !== null) {
            // jika ada, set kategori sudah terdaftar.
            return redirect()->back()->withErrors(['errors' => 'Kategori sudah terdaftar!']);
         } else {
            // process add kategori ke db
             $kategori = Kategori::create([
            'kategori' => $request->kategori,
            'created_date' => now(),
            'created_by' => Auth::user()->id,
            
        ]);

        //insert ke log activity
        if ($kategori->wasRecentlyCreated) {
            Activity::create([
                'activity' => 'Add Kategori',
                'name_user' => Auth::user()->name,
                'nama_barang' => $request->kategori,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->back()->with('success', 'Add new kategori success.');
        } else {
            return redirect()->back()->withErrors(['errors' => 'Gagal menambahkan data kategori.'])->withInput();
        }

        $request->session()->flash('success', 'Add New Category Item Success');
          return redirect()->back()->with('success', 'Add Category success!');
         }

        return redirect('/kategori');
    }

    public function deleteKategori(Request $request)
    {
        $kategori = Kategori::find($request->id);

        if (!$kategori) {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan.');
        } else {
            $kategori->delete();
            
            // insert list activity
            Activity::insert([
                'activity' => 'Delete Kategori',
                'name_user' => Auth::user()->name,
                'nama_barang' => $kategori->kategori,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return redirect()->back()->with('success', 'Delete Kategori berhasil.');
        }

    }
        public function getRepack() {
            $vendor = DB::table('master_vendor')->where('created_by',Auth::user()->id)->get();

            $kerupuk = DB::table('master_barang_v1')
                    ->select('master_barang_v1.*')
                    ->where('created_user_id',Auth::user()->id)
                    ->orderBy('nama_barang', 'asc')
                    ->get();

                $repack = DB::table('repack_product')
                                    ->Join('master_vendor', 'master_vendor.vendor_id', '=', 'repack_product.vendor_id')
                                ->Join('master_barang_v1', 'master_barang_v1.id_barang', '=', 'repack_product.product_id')
                                    ->select('master_barang_v1.*', 'master_vendor.*', 'repack_product.*')
                                    ->where('created_user_id',Auth::user()->id)
                                ->orderBy('nama_barang', 'asc')
                                ->get();

            return view('admin.repack', compact('vendor', 'kerupuk', 'repack'));
        }

        public function addRepack(Request $request) 
        {
            $validator = Validator::make($request->all(), [
                'vendorID' => 'required',
                'barangID' => 'required',
                'base_nett' => 'required',
                'repack_nett' => 'required',
                'base_qty' => 'required',
                'repack_qty' => 'required',
                'base_weight' => 'required',
                'repack_weight' => 'required',
            ], [
                'vendorID.required' => 'Vendor tidak boleh kosong',
                'barangID.required' => 'Product tidak boleh kosong',
                'base_nett.required' => 'Base nett tidak boleh kosong',
                'repack_nett.required' => 'Repack Nett tidak boleh kosong',
                'base_qty.required' => 'Base Qty tidak boleh kosong',
                'repack_qty.required' => 'Repack Qty tidak boleh kosong',
                'base_weight.required' => 'Base Weight tidak boleh kosong',
                'repack_weight.required' => 'Repack Weight tidak boleh kosong',
            ]);
    
            if ($validator->fails()) {
               return back()->withErrors($validator)->withInput();
            }
    
            //validasi - ntar lagi.
            if (Kategori::where('kategori', $request->kategori)->exists()) {
                
                $validator->errors()->add('kategori', 'Kategori sudah terdaftar!');
               // return back()->withErrors($validator)->withInput();
            }
    
            // tambah validasi 'and user_id = Auth->id 
            // $validation = ['kategori'=> $request->kategori, 'created_by' => Auth::user()->id ];
            //  $kategori =   Kategori::where([
            //     ['kategori','=',  $request->kategori],
            //     ['created_by', '=', Auth::user()->id]
            //     ])->get()->first();
    
            //  if ($kategori !== null) {
            //     // jika ada, set kategori sudah terdaftar.
            //     return redirect()->back()->withErrors(['errors' => 'Kategori sudah terdaftar!']);
            //  } else {
                // process add kategori ke db
                 $repack = Repack::create([
                'vendor_id' => $request->vendorID,
                'product_id' => $request->barangID,
                'base_weight' => $request->base_weight,
                'base_qty' => $request->base_qty,
                'repack_weight' => $request->repack_weight,
                'repack_qty' => $request->repack_qty,
                'base_nett' => $request->base_nett,
                'repack_nett' => $request->repack_nett,
                'created_date' => now(),
                'created_by' => Auth::user()->id,
                
            ]);
    
            //insert ke log activity
            if ($repack->wasRecentlyCreated) {
                Activity::create([
                    'activity' => 'Add Repack Product',
                    'name_user' => Auth::user()->name,
                    'nama_barang' => $request->barangID,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                return redirect()->back()->with('success', 'Add new kategori success.');
            } else {
                return redirect()->back()->withErrors(['errors' => 'Gagal menambahkan data kategori.'])->withInput();
            }
    
            $request->session()->flash('success', 'Add New Category Item Success');
              return redirect()->back()->with('success', 'Add Category success!');
         //    }
    
            return redirect('/kategori');
        }

}
