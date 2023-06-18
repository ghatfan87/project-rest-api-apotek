<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Apotek extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'nama',
        'rujukan',
        'rumah_sakit',
        'obat',
        'harga_satuan',
        'total_harga',
        'apoteker',
    ];
    
    protected $casts = [
        'obat' => 'array',
        'harga_satuan' => 'array',
    ];
    

    public function getObatAttribute($value)
    {
        return array_map(fn ($item) => trim($item, '"'), explode(',', $value));
    }
    
    public function getHargaSatuanAttribute($value)
    {
        return array_map(fn ($item) => trim($item, '"'), explode(',', $value));
    }

}
