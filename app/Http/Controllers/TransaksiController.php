<?php

namespace App\Http\Controllers;

use App\Models\Kerupuk;
use App\Models\Transaksi;
use App\Models\Kategori;
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
        
        $kerupuk = Kerupuk::get();

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : null;
        $start = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null;
        $end = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null;

        $transaksi = Transaksi::select('*')
        ->when($selectedDate, function ($query) use ($selectedDate) {
            $query->whereDate('created_at', $selectedDate)
            ->where('created_by',Auth::user()->id);
        })
        ->when($start && $end, function ($query) use ($start, $end) {
            $query->where('created_at', '>=', $start)
                ->where('created_at', '<', Carbon::parse($end)->addDay())
                ->where('created_by',Auth::user()->id);
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

        $kerupuk = Kerupuk::get();

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

        $kerupuk = Kerupuk::find($request->kerupukID);

        if ($kerupuk && $kerupuk->stok >= $request->qty) {
            Transaksi::insert([
                'kerupukID' => $request->kerupukID,
                'nama_barang' => $request->nama_barang,
                'qty' => $request->qty,
                'modal' => $request->modal,
                'satuan' => $request->satuan,
                'subtotal' => $request->subtotal,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->id,
            ]);

            $kerupuk->stok -= $request->qty;
            $kerupuk->save();

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
        return response()->json($data);
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

}
