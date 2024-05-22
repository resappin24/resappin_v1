<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    protected $table = "master_vendor";
    protected $primaryKey = 'vendor_id';
    protected $fillable = [
        'kode_vendor',
        'nama_vendor',
        'alamat',
        'no_telp',
        'created_date',
        'created_by'
    ];
}
