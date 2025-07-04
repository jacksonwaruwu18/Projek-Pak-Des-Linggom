<?php

namespace App\Models;

use App\JenisTransaksi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiStock extends Model
{
    protected $table = 'transaksi_stocks';

    protected $fillable = [
        'stock_id',
        'tanggal',
        'keterangan',
        'jumlah',
        'jenis',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jenis' => JenisTransaksi::class,
    ];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'id');
    }
}