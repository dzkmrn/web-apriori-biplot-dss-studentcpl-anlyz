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
        Schema::create('data_histori', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->integer('angkatan');
            $table->text('deskripsi');
            $table->json('hasil_analisis'); // Menyimpan hasil analisis
            $table->double('min_support', 8, 4)->default(0.05);
            $table->double('min_confidence', 8, 4)->default(0.3);
            $table->integer('total_rules')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_histori');
    }
};
