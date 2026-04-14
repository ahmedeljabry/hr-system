<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('leave_request_actions')) {
            return;
        }

        Schema::table('leave_request_actions', function (Blueprint $table) {
            if (!Schema::hasColumn('leave_request_actions', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('leave_request_id');
            }

            if (!Schema::hasColumn('leave_request_actions', 'employee_id')) {
                $table->unsignedBigInteger('employee_id')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('leave_request_actions', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->after('employee_id');
            }

            if (!Schema::hasColumn('leave_request_actions', 'actor_name')) {
                $table->string('actor_name')->nullable()->after('client_id');
            }

            if (!Schema::hasColumn('leave_request_actions', 'actor_role')) {
                $table->string('actor_role')->nullable()->after('actor_name');
            }

            if (!Schema::hasColumn('leave_request_actions', 'old_values')) {
                $table->json('old_values')->nullable()->after('notes');
            }

            if (!Schema::hasColumn('leave_request_actions', 'new_values')) {
                $table->json('new_values')->nullable()->after('old_values');
            }
        });

        if (
            DB::getDriverName() === 'mysql'
            && Schema::hasColumn('leave_request_actions', 'performed_by_user_id')
            && Schema::hasColumn('leave_request_actions', 'user_id')
        ) {
            DB::statement('
                UPDATE leave_request_actions
                SET user_id = performed_by_user_id
                WHERE user_id IS NULL AND performed_by_user_id IS NOT NULL
            ');
        }

        if (
            DB::getDriverName() === 'mysql'
            && Schema::hasColumn('leave_request_actions', 'employee_id')
            && Schema::hasColumn('leave_request_actions', 'client_id')
        ) {
            DB::statement('
                UPDATE leave_request_actions AS actions
                INNER JOIN leave_requests AS requests ON requests.id = actions.leave_request_id
                SET
                    actions.employee_id = COALESCE(actions.employee_id, requests.employee_id),
                    actions.client_id = COALESCE(actions.client_id, requests.client_id)
            ');
        }
    }

    public function down(): void
    {
        // Repair migration for legacy databases. It intentionally does not drop columns on rollback.
    }
};
