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
        $item = DB::table('master_barang_v1')->where('created_user_id',Auth::user()->id)->get();

       $kategori = DB::table('kategori_barang')->where('created_by',Auth::user()->id)->get();
        return view('admin.prod_category', compact('kategori','item'));

    //    $prod_cat = 

    }
}
