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
        Schema::create('alokasi_dana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kampanye_id')->constrained('kampanye');
            $table->string('deskripsi');
            $table->bigInteger('jumlah_digunakan');
            $table->string('url_foto_bukti')->nullable();
            $table->date('tanggal_pengeluaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alokasi_dana');
    }
};
