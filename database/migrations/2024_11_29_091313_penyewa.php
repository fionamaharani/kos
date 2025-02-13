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
        Schema::create('penyewa', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('nama');
            $table->string('no_telepon');
            $table->string('no_kamar');
            $table->string('tipe_kos');
            $table->string('alamat');
            $table->string('ktp');
            $table->string('tanggal_menyewa');
            $table->string('tanggal_jatuh_tempo');
            $table->string('tanggal_booking');
            $table->boolean('status_penyewaan')->default(false);
            $table->string('tanggal_berakhir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyewa');
    }
};
