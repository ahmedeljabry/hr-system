<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('leave_requests', 'resumption_at')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                $table->dateTime('resumption_at')->nullable()->after('reviewed_at');
            });
        }

        if (!Schema::hasColumn('leave_requests', 'resumption_notes')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                $table->text('resumption_notes')->nullable()->after('resumption_at');
            });
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE leave_requests MODIFY status ENUM('pending', 'approved', 'rejected', 'postponed') NOT NULL DEFAULT 'pending'");
        }

        if (!Schema::hasTable('leave_request_actions')) {
            Schema::create('leave_request_actions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('leave_request_id')->constrained('leave_requests')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
                $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
                $table->string('actor_name')->nullable();
                $table->string('actor_role')->nullable();
                $table->string('action');
                $table->text('notes')->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_request_actions');

        if (Schema::hasColumn('leave_requests', 'resumption_notes') || Schema::hasColumn('leave_requests', 'resumption_at')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                if (Schema::hasColumn('leave_requests', 'resumption_notes')) {
                    $table->dropColumn('resumption_notes');
                }

                if (Schema::hasColumn('leave_requests', 'resumption_at')) {
                    $table->dropColumn('resumption_at');
                }
            });
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE leave_requests MODIFY status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
        }
    }
};
