<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repack extends Model
{
    use HasFactory;
    protected $table = "repack_product";
    protected $primaryKey = 'id_repack';
    protected $fillable = [
        'vendor_id',
        'product_id',
        'base_weight',
        'base_qty',
        'repack_weight',
        'repack_qty',
        'base_nett',
        'repack_nett',
        'created_date',
        'created_by'
    ];

    public $timestamps = false;
}
