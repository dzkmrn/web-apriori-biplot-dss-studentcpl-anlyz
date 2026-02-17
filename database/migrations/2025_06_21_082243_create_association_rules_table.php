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
        Schema::create('association_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_histori_id')->constrained('data_histori')->onDelete('cascade');
            $table->json('lhs'); // Left Hand Side (IF part)
            $table->json('rhs'); // Right Hand Side (THEN part) 
            $table->double('support', 8, 4);
            $table->double('confidence', 8, 4);
            $table->double('lift', 8, 4);
            $table->string('rule_type', 10); // '1to1' or '2to1'
            $table->text('interpretation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('association_rules');
    }
};
