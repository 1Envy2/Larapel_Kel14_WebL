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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->uuid('transaction_id')->unique();
            $table->bigInteger('donor_id')->nullable();
            $table->bigInteger('campaign_id');
            $table->decimal('amount', 15, 2);
            $table->bigInteger('payment_method_id');
            $table->enum('status', ['pending', 'successful', 'failed'])->default('pending');
            $table->string('proof_image')->nullable();
            $table->text('message')->nullable();
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->boolean('anonymous')->default(false);
            $table->string('status_locked')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
