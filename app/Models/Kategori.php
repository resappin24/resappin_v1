<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "kategori_barang";
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'kategori',
        'created_date',
        'created_by',
    ];
}
