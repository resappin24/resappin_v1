<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangV1 extends Model
{
    use HasFactory;
    protected $table = "master_barang_v1";
    protected $primaryKey = 'id_barang';
    protected $fillable = [
        'vendor_id',
        'nama_barang',
        'main_stok',
        'main_harga_beli',
        'main_harga_jual',
        'new_stok',
        'new_harga_beli',
        'new_harga_jual',
        'gambar_barang',
        'tanggal_beli',
        'created_date',
        'updated_date',
        'created_user_id',
    ];

    public $timestamps = false;
}
