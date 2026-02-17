<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum to include '3to1'
        DB::statement("ALTER TABLE association_rules MODIFY COLUMN rule_type ENUM('1to1', '2to1', '3to1')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE association_rules MODIFY COLUMN rule_type ENUM('1to1', '2to1')");
    }
};
