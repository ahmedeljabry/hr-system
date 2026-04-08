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
            $table->string('status')->default('active')->after('hire_date'); // active, terminated
        });

        Schema::create('employee_terminations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->integer('reason_case'); // 1-11
            $table->string('article_number')->nullable();
            $table->string('notice_period')->nullable();
            $table->text('comments')->nullable();
            $table->json('files')->nullable();
            $table->date('terminated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_terminations');
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
