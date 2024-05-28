<?php

namespace App\Http\Controllers;

use App\Models\Kerupuk;
use App\Models\Transaksi;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            $query->whereDate('created_at', $selectedDate);
        })
        ->when($start && $end, function ($query) use ($start, $end) {
            $query->where('created_at', '>=', $start)
                ->where('created_at', '<', Carbon::parse($end)->addDay());
        })
        ->when(!$selectedDate && !$start && !$end, function ($query) {
            $query->whereDate('created_at', Carbon::now()->toDateString());
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


}
