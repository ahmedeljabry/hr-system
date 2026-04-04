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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('position');
            $table->string('national_id_number');
            $table->string('national_id_image')->nullable();
            $table->string('contract_image')->nullable();
            $table->decimal('basic_salary', 10, 2);
            $table->date('hire_date');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['client_id', 'national_id_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
