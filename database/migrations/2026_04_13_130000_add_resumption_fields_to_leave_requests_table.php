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
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->timestamp('resumed_at')->nullable()->after('reviewed_at');
            $table->timestamp('resumption_recorded_at')->nullable()->after('resumed_at');

            $table->index(['employee_id', 'status', 'resumed_at'], 'leave_requests_employee_status_resumed_idx');
            $table->index(['client_id', 'status', 'resumed_at'], 'leave_requests_client_status_resumed_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropIndex('leave_requests_employee_status_resumed_idx');
            $table->dropIndex('leave_requests_client_status_resumed_idx');
            $table->dropColumn(['resumed_at', 'resumption_recorded_at']);
        });
    }
};
