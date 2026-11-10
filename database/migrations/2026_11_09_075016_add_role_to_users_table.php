<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // The role column is added by the earlier role migration.
    }

    public function down(): void
    {
        // The earlier role migration owns the column and its rollback.
    }
};
