<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produkdetail extends Model
{
    use HasFactory;
    protected $table='produk_detail';
    protected $fillable = [
        'id_produk','gambar', 'id_kategori', 'hpp', 'harga_jual','stok'
    ];
}
