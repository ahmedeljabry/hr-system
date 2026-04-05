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
            $table->decimal('housing_allowance', 10, 2)->default(0)->after('basic_salary');
            $table->decimal('transportation_allowance', 10, 2)->default(0)->after('housing_allowance');
            $table->decimal('other_allowances', 10, 2)->default(0)->after('transportation_allowance');
            $table->string('cv_file')->nullable()->after('contract_image');
            $table->text('other_documents')->nullable()->after('cv_file'); // Stores path array as JSON
            $table->string('bank_iban')->nullable()->after('national_id_number');
            $table->string('phone')->nullable()->after('bank_iban');
            $table->string('emergency_phone')->nullable()->after('phone');
            $table->string('email')->nullable()->after('emergency_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'housing_allowance',
                'transportation_allowance',
                'other_allowances',
                'cv_file',
                'other_documents',
                'bank_iban',
                'phone',
                'emergency_phone',
                'email'
            ]);
        });
    }
};
