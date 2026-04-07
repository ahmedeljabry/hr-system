<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->index('date');
        });

        Schema::table('payroll_runs', function (Blueprint $table) {
            $table->index('month');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->dropIndex(['date']);
        });

        Schema::table('payroll_runs', function (Blueprint $table) {
            $table->dropIndex(['month']);
            $table->dropIndex(['status']);
        });
    }
};
