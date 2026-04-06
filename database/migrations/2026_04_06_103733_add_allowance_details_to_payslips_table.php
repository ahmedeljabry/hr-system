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
        Schema::table('payslips', function (Blueprint $table) {
            $table->decimal('housing_allowance', 15, 2)->default(0)->after('basic_salary');
            $table->decimal('transportation_allowance', 15, 2)->default(0)->after('housing_allowance');
            $table->decimal('other_allowances', 15, 2)->default(0)->after('transportation_allowance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            $table->dropColumn(['housing_allowance', 'transportation_allowance', 'other_allowances']);
        });
    }
};
