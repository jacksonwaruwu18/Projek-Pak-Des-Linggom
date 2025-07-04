<?php

namespace App\Filament\Resources\TransaksiStockResource\Pages;

use App\Filament\Resources\TransaksiStockResource;
use App\JenisTransaksi;
use App\Models\Stock;
use App\Models\TransaksiStock;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateTransaksiStock extends CreateRecord
{
    protected static string $resource = TransaksiStockResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(
            function () use ($data) {
                // cari data stock, buat jika belum ada
                $stock = Stock::firstOrCreate(
                    [
                        'barang_id' => $data['barang_id'],
                        'gudang_id' => $data['gudang_id']
                    ],
                    [
                        'barang_id' => $data['barang_id'],
                        'gudang_id' => $data['gudang_id'],
                        'balance' => 0,
                    ]
                );

                // lock stock untuk di update
                $stock = Stock::lockForUpdate()
                    ->find($stock->id);

                if ($data['jenis'] === JenisTransaksi::Debit->value) {
                    $trx = TransaksiStock::create([
                        'stock_id' => $stock->id,
                        'tanggal' => $data['tanggal'],
                        'keterangan' => $data['keterangan'],
                        'jenis' => $data['jenis'],
                        'jumlah' => $data['jumlah'],
                    ]);

                    $stock->balance += $data['jumlah'];
                    $stock->save();

                    return $trx;
                }

                // transaksi kredit
                // jika stock tidak cukup, error, hentikan proses
                if ($data['jumlah'] > $stock->balance) {
                    Notification::make()
                        ->title('Stock barang tidak mencukupi')
                        ->danger()
                        ->send();
                    $this->halt();
                }

                // kurangi stock sebanyak jumlah transaksi
                $trx = TransaksiStock::create([
                        'stock_id' => $stock->id,
                        'tanggal' => $data['tanggal'],
                        'keterangan' => $data['keterangan'],
                        'jenis' => $data['jenis'],
                        'jumlah' => $data['jumlah'],
                    ]);

                $stock->balance -= $data['jumlah'];
                $stock->save();
                return $trx;
            }
        );
    }
}