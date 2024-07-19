<?php

namespace App\Http\Controllers;

use App\Models\ProductCategoryModel;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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


        $prod_cat = DB::table('master_barang_v1')
                    ->Join('product_category', 'product_category.barang_id', '=', 'master_barang_v1.id_barang')
                    ->join('kategori_barang', 'product_category.category_id', '=', 'kategori_barang.id')
                    ->select('master_barang_v1.*', 'kategori_barang.*')
                    ->where('created_user_id',Auth::user()->id)
                    ->orderBy('kategori', 'asc')
                    ->get();

        return view('admin.prod_category', compact('kategori','barang','prod_cat'));

    }

    public function addProdKategori(Request $request){
        $validator = Validator::make($request->all(), [
             'barangID' => 'required',
            'categoryID' => 'required'
        ], [
            'categoryID.required' => 'Kategori tidak boleh kosong'
            
        ]);

        if ($validator->fails()) {
           return back()->withErrors($validator)->withInput();
        }

        //validasi sudah terdaftar....
        // if (Kategori::where('kategori', $request->kategori)->exists()) {
            
        //     $validator->errors()->add('kategori', 'Kategori sudah terdaftar!');
           // return back()->withErrors($validator)->withInput();
        // }

        // tambah validasi 'and user_id = Auth->id 
    /*    $validation = ['kategori'=> $request->kategori, 'created_by' => Auth::user()->id ];
         $kategori =   Kategori::where([
            ['kategori','=',  $request->kategori],
            ['created_by', '=', Auth::user()->id]
            ])->get()->first(); */

        //  if ($kategori !== null) {
        //     // jika ada, set kategori sudah terdaftar.
        //     return redirect()->back()->withErrors(['errors' => 'Sorry, Product Kategori sudah terdaftar!']);
        //  } else {
            // process add kategori ke db
             $kategori = ProductCategoryModel::create([
            'barang_id' => $request->barangID,
            'category_id' => $request->categoryID,
            'created_date' => now(),
            'created_by' => Auth::user()->id,
            
        ]);

        //ambil nama barangnya...
        $barang = DB::table('master_barang_v1')
                    ->select('master_barang_v1.*')
                    ->where('id_barang',$request->barangID)
                    ->first();

                
        //insert ke log activity
        if ($kategori->wasRecentlyCreated) {
            Activity::create([
                'activity' => 'Add Product Category',
                'name_user' => Auth::user()->name,
                'nama_barang' => $barang->nama_barang,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->back()->with('success', 'Add New Product Category success.');
        } else {
            return redirect()->back()->withErrors(['errors' => 'Gagal menambahkan data kategori.'])->withInput();
        }

        $request->session()->flash('success', 'Add New Product Category Item Success');
          return redirect()->back()->with('success', 'Add Product Category success!');
       //  }

        return redirect('/prod_category');
    }
}
