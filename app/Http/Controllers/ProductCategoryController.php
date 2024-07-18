<?php

namespace App\Http\Controllers;

use App\Models\ProductCategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends Controller
{
    public function get_prod_kategori() {
        $data = ProductCategoryModel::get();
        return response()->json($data);
    }

    public function prod_kategori($order = 'asc')
    {
        $barang = DB::table('master_barang_v1')->where('created_user_id',Auth::user()->id)->get();

       $kategori = DB::table('kategori_barang')->where('created_by',Auth::user()->id)->get();

        return view('admin.prod_category', compact('kategori','barang'));

    //    $prod_cat = 

    }

    public function addProdCat(Request $request){
        $validator = Validator::make($request->all(), [
             'nama_barang' => 'required',
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
            return redirect()->back()->withErrors(['errors' => 'Sorry, Product Kategori sudah terdaftar!']);
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
            return redirect()->back()->with('success', 'Add New Product Category success.');
        } else {
            return redirect()->back()->withErrors(['errors' => 'Gagal menambahkan data kategori.'])->withInput();
        }

        $request->session()->flash('success', 'Add New Category Item Success');
          return redirect()->back()->with('success', 'Add Category success!');
         }

        return redirect('/prod_category');
    }
}
