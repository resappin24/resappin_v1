<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = "transaksi";
    protected $primaryKey = 'transaksiID';
    protected $fillable = [
        'kerupukID',
        'qty',
        'created_at',
        'updated_at',
        'created_by'
    ];
}
