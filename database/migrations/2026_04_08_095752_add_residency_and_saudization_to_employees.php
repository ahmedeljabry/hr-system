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
            $table->string('nationality')->nullable()->after('gender');
            $table->string('residency_number')->nullable()->after('nationality');
            $table->date('residency_start_date')->nullable()->after('residency_number');
            $table->date('residency_end_date')->nullable()->after('residency_start_date');
            $table->decimal('saudization_percentage', 5, 2)->default(0)->after('residency_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'nationality',
                'residency_number',
                'residency_start_date',
                'residency_end_date',
                'saudization_percentage'
            ]);
        });
    }
};
