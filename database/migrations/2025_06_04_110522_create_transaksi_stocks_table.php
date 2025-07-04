<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')
                ->references('id')
                ->on('stocks')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->date('tanggal');
            $table->string('keterangan');
            $table->unsignedInteger('jumlah');
            $table->string('jenis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_stocks');
    }
};