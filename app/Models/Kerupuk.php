<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kerupuk extends Model
{
    use HasFactory;
    protected $table = "kerupuk";
    protected $primaryKey = 'kerupukID';
    protected $fillable = [
        'nama_barang',
        'stok',
        'harga_beli',
        'harga_jual',
        'gambar_barang',
        'created_at',
        'updated_at',
        'created_user_id',
    ];
}
