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
        Schema::table('employees', function (Blueprint $table) {
            $table->time('shift_start_time')->nullable();
            $table->time('shift_end_time')->nullable();
            $table->string('work_type')->nullable(); // full-time, part-time, remote, temporary, casual, seasonal
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['shift_start_time', 'shift_end_time', 'work_type']);
        });
    }
};
