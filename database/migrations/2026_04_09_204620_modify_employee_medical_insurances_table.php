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
        Schema::table('employee_medical_insurances', function (Blueprint $table) {
            $table->foreignId('insurance_policy_id')->nullable()->after('employee_id')->constrained('insurance_policies')->onDelete('cascade');
            $table->string('insurance_class')->nullable()->after('insurance_policy_id');
            $table->decimal('cost', 10, 2)->nullable()->after('insurance_class');
            
            // Drop redundant columns if they exist
            $table->dropForeign(['insurance_company_id']);
            $table->dropColumn(['insurance_company_id', 'policy_number', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_medical_insurances', function (Blueprint $table) {
            $table->dropForeign(['insurance_policy_id']);
            $table->dropColumn(['insurance_policy_id', 'insurance_class', 'cost']);
            
            $table->foreignId('insurance_company_id')->constrained('insurance_companies')->onDelete('cascade');
            $table->string('policy_number')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });
    }
};
