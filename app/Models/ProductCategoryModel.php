<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "product_category";
    protected $primaryKey = 'id';
    protected $fillable = [
        'prod_cat_id',
        'barang_id',
        'category_id',
        'created_date',
        'created_by',
    ];

}
