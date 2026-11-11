<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->unsignedTinyInteger('year_level')->nullable()->after('semester');
        });

        // Backfill existing rows with the student's current year level so
        // historical records get a sensible default instead of "not set".
        DB::table('enrollments')->update([
            'year_level' => DB::raw('(select year_level from students where students.id = enrollments.student_id)'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn('year_level');
        });
    }
};
