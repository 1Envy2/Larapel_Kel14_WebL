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
        Schema::create('kampanye', function (Blueprint $table) {
            $table->id();
            $table->string('url_foto')->nullable();
            $table->string('judul');
            $table->text('deskripsi');
            $table->bigInteger('target_dana');
            $table->bigInteger('terkumpul')->default(0);
            $table->string('status');
            $table->foreignId('kategori_id')->constrained('kategori');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kampanye');
    }
};
