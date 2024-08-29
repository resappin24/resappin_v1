<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "activity";
    protected $primaryKey = 'activityID';
    protected $fillable = [
        'activity',
        'name_user',
        'nama_barang',
        'created_at',
    ];
}
