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
        Schema::create('donasi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('jumlah');
            $table->string('metode_pembayaran');
            $table->enum('status', ['pending', 'berhasil', 'gagal'])->default('pending');
            $table->foreignId('pengguna_id')->nullable()->constrained('users');
            $table->foreignId('kampanye_id')->constrained('kampanye');
            $table->boolean('apakah_anonim')->default(false);
            $table->string('id_transaksi')->nullable();
            $table->json('rincian_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donasi');
    }
};
