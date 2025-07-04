<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gudang extends Model
{
    use HasFactory; 

    protected $table = 'gudangs';

    protected $fillable = [
        'nama',
        'status',
    ];

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class, 'barang_id', 'id');
    }
}
